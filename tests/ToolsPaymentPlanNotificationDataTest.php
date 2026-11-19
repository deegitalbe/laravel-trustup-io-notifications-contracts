<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\EmailSender;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsPaymentPlanConfirmationNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsPaymentPlanInstallmentDueNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\EmailTemplateLocaleGranularity;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationChannel;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\SenderRole;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\Source;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidNotificationDataException;
use Deegitalbe\TrustupIoNotificationsContracts\Request\RequestPayload;

dataset('payment plan emails', [
    'confirmation' => [ToolsPaymentPlanConfirmationNotificationData::class, 'paymentPlanConfirmationPayload'],
    'installment due' => [ToolsPaymentPlanInstallmentDueNotificationData::class, 'paymentPlanInstallmentDuePayload'],
]);

/** @return array<string, mixed> */
function paymentPlanConfirmationPayload(): array
{
    return [
        'plan_email_id' => 'plan-email-uuid',
        'plan_code' => 'PLAN-2026-0042',
        'created_on' => '17/11/2026',
        'installments' => [
            ['position' => 1, 'due_on' => '01/12/2026', 'amount' => '250,00 €'],
            ['position' => 2, 'due_on' => '01/01/2027', 'amount' => '250,00 €'],
        ],
        'total' => '500,00 €',
        'beneficiary' => 'Trustup SA',
        'iban' => 'BE68539007547034',
        'bic' => 'GKCCBEBB',
        'reference' => '+++123/4567/89012+++',
    ];
}

/** @return array<string, mixed> */
function paymentPlanInstallmentDuePayload(): array
{
    return [
        'plan_email_id' => 'plan-email-uuid',
        'plan_code' => 'PLAN-2026-0042',
        'position' => 2,
        'installment_count' => 3,
        'due_on' => '01/01/2027',
        'amount' => '250,00 €',
        'beneficiary' => 'Trustup SA',
        'iban' => 'BE68539007547034',
        'bic' => 'GKCCBEBB',
        'reference' => '+++123/4567/89012+++',
    ];
}

/**
 * @param  array<string, mixed>  $data
 * @return array<string, mixed>
 */
function paymentPlanRequest(NotificationType $type, array $data): array
{
    return [
        'type' => $type->value,
        'recipient' => ['identified' => false, 'email' => 'marie@example.test', 'phone' => null, 'device_tokens' => [], 'locale' => 'fr-BE', 'source' => 't360'],
        'data' => $data,
        'channels' => [NotificationChannel::Email->value],
    ];
}

it('builds the payment plan confirmation data class from a request, values unchanged', function (): void {
    $type = NotificationType::ToolsPaymentPlanConfirmationNotification;

    $payload = RequestPayload::fromArray(paymentPlanRequest($type, paymentPlanConfirmationPayload()));

    expect($payload->data)->toBeInstanceOf(ToolsPaymentPlanConfirmationNotificationData::class)
        ->and($payload->data->notificationType())->toBe($type)
        ->and($payload->data->toArray())->toBe(paymentPlanConfirmationPayload());
});

it('builds the installment due data class from a request, integers kept as integers', function (): void {
    $type = NotificationType::ToolsPaymentPlanInstallmentDueNotification;

    $payload = RequestPayload::fromArray(paymentPlanRequest($type, paymentPlanInstallmentDuePayload()));

    expect($payload->data)->toBeInstanceOf(ToolsPaymentPlanInstallmentDueNotificationData::class)
        ->and($payload->data->notificationType())->toBe($type)
        ->and($payload->data->toArray())->toBe(paymentPlanInstallmentDuePayload());
});

it('is sent by the credit control role', function (string $class, string $payload): void {
    $data = new $class(...$payload());

    expect($data->emailSender())->toEqual(new EmailSender(SenderRole::CreditControl));
})->with('payment plan emails');

it('refuses the whole confirmation request when a required field is missing', function (string $key): void {
    $data = paymentPlanConfirmationPayload();
    unset($data[$key]);

    expect(fn () => RequestPayload::fromArray(paymentPlanRequest(NotificationType::ToolsPaymentPlanConfirmationNotification, $data)))
        ->toThrow(InvalidNotificationDataException::class);
})->with(['plan_email_id', 'plan_code', 'created_on', 'installments', 'total', 'beneficiary', 'iban', 'bic', 'reference']);

it('refuses the whole installment due request when a required field is missing', function (string $key): void {
    $data = paymentPlanInstallmentDuePayload();
    unset($data[$key]);

    expect(fn () => RequestPayload::fromArray(paymentPlanRequest(NotificationType::ToolsPaymentPlanInstallmentDueNotification, $data)))
        ->toThrow(InvalidNotificationDataException::class);
})->with(['plan_email_id', 'plan_code', 'position', 'installment_count', 'due_on', 'amount', 'beneficiary', 'iban', 'bic', 'reference']);

it('keeps the current postmark alias with per-language granularity', function (string $class, string $payload, string $alias): void {
    $data = new $class(...$payload());

    expect($data->emailTemplate())->toBe($alias)
        ->and($data->emailTemplateLocaleGranularity())->toBe(EmailTemplateLocaleGranularity::Language);
})->with([
    'confirmation' => [ToolsPaymentPlanConfirmationNotificationData::class, 'paymentPlanConfirmationPayload', 'tools-payment-plan-confirmation-notification'],
    'installment due' => [ToolsPaymentPlanInstallmentDueNotificationData::class, 'paymentPlanInstallmentDuePayload', 'tools-payment-plan-installment-due-notification'],
]);

it('hands exactly the 360 template keys to the email template', function (string $class, string $payload): void {
    $data = new $class(...$payload());

    expect($data->toEmail()->variables)->toBe($payload());
})->with('payment plan emails');

it('belongs to the t360 source', function (string $class, string $payload): void {
    $data = new $class(...$payload());

    expect($data->notificationType()->source())->toBe(Source::T360)
        ->and(NotificationType::forSource(Source::T360))->toContain($data->notificationType());
})->with('payment plan emails');
