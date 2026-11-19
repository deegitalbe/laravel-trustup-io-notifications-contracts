<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\EmailSender;
use Deegitalbe\TrustupIoNotificationsContracts\Data\T360OnboardingCancelledNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\T360OnboardingConfirmationNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\T360OnboardingInvitationNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\T360OnboardingNoShowNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\T360OnboardingReminderNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\T360OnboardingRescheduledNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\EmailTemplateLocaleGranularity;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationChannel;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\SenderRole;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\Source;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidNotificationDataException;
use Deegitalbe\TrustupIoNotificationsContracts\Request\RequestPayload;

dataset('t360 onboarding types', [
    'invitation' => [NotificationType::T360OnboardingInvitationNotification, T360OnboardingInvitationNotificationData::class],
    'confirmation' => [NotificationType::T360OnboardingConfirmationNotification, T360OnboardingConfirmationNotificationData::class],
    'reminder' => [NotificationType::T360OnboardingReminderNotification, T360OnboardingReminderNotificationData::class],
    'rescheduled' => [NotificationType::T360OnboardingRescheduledNotification, T360OnboardingRescheduledNotificationData::class],
    'cancelled' => [NotificationType::T360OnboardingCancelledNotification, T360OnboardingCancelledNotificationData::class],
    'no show' => [NotificationType::T360OnboardingNoShowNotification, T360OnboardingNoShowNotificationData::class],
]);

/** @return array<string, string> */
function t360OnboardingPayload(): array
{
    return [
        'onboarding_email_id' => 'email-uuid',
        'subject' => 'Votre onboarding Trustup',
        'html_body' => '<p>Bonjour <strong>Marie</strong> &amp; bienvenue</p>',
        'text_body' => "Bonjour Marie & bienvenue\n",
    ];
}

/**
 * @param  array<string, mixed>  $data
 * @return array<string, mixed>
 */
function t360OnboardingRequest(NotificationType $type, array $data): array
{
    return [
        'type' => $type->value,
        'recipient' => ['identified' => false, 'email' => 'marie@example.test', 'phone' => null, 'device_tokens' => [], 'locale' => 'fr-BE', 'source' => 't360'],
        'data' => $data,
        'channels' => [NotificationChannel::Email->value],
    ];
}

it('builds the onboarding data class of its type from a request, values unchanged', function (NotificationType $type, string $class): void {
    $payload = RequestPayload::fromArray(t360OnboardingRequest($type, t360OnboardingPayload()));

    expect($payload->data)->toBeInstanceOf($class)
        ->and($payload->data->notificationType())->toBe($type)
        ->and($payload->data->toArray())->toBe(t360OnboardingPayload());
})->with('t360 onboarding types');

it('refuses the whole request when a required field is missing', function (NotificationType $type, string $class, string $key): void {
    $data = t360OnboardingPayload();
    unset($data[$key]);

    expect(fn () => RequestPayload::fromArray(t360OnboardingRequest($type, $data)))->toThrow(InvalidNotificationDataException::class);
})->with('t360 onboarding types')->with(['onboarding_email_id', 'subject', 'html_body', 'text_body']);

it('uses the shared onboarding template with per-language granularity', function (NotificationType $type, string $class): void {
    $data = new $class(...t360OnboardingPayload());

    expect($data->emailTemplate())->toBe('t360-onboarding-notification')
        ->and($data->emailTemplateLocaleGranularity())->toBe(EmailTemplateLocaleGranularity::Language);
})->with('t360 onboarding types');

it('is sent by the onboarding role under the Trustup Onboarding name', function (NotificationType $type, string $class): void {
    $data = new $class(...t360OnboardingPayload());

    expect($data->emailSender())->toEqual(new EmailSender(SenderRole::Onboarding, 'Trustup Onboarding'));
})->with('t360 onboarding types');

it('hands subject, bodies and 360 id to the email template without rewriting them', function (NotificationType $type, string $class): void {
    $data = new $class(...t360OnboardingPayload());

    expect($data->toEmail()->variables)->toBe(t360OnboardingPayload());
})->with('t360 onboarding types');

it('belongs to the t360 source', function (NotificationType $type): void {
    expect($type->source())->toBe(Source::T360)
        ->and(NotificationType::forSource(Source::T360))->toContain($type);
})->with('t360 onboarding types');
