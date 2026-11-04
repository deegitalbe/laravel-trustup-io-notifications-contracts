<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\EmailSender;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsUnpaidLevel1NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsUnpaidLevel2NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsUnpaidLevel3NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsUnpaidLevel4NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsUnpaidLevelNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\EmailTemplateLocaleGranularity;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\SenderRole;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidNotificationDataException;

dataset('unpaid levels', [
    'level 1' => [ToolsUnpaidLevel1NotificationData::class, NotificationType::ToolsUnpaidLevel1Notification, 'tools-unpaid-level-1-notification'],
    'level 2' => [ToolsUnpaidLevel2NotificationData::class, NotificationType::ToolsUnpaidLevel2Notification, 'tools-unpaid-level-2-notification'],
    'level 3' => [ToolsUnpaidLevel3NotificationData::class, NotificationType::ToolsUnpaidLevel3Notification, 'tools-unpaid-level-3-notification'],
    'level 4' => [ToolsUnpaidLevel4NotificationData::class, NotificationType::ToolsUnpaidLevel4Notification, 'tools-unpaid-level-4-notification'],
]);

/**
 * @param  class-string<ToolsUnpaidLevelNotificationData>  $class
 * @param  array<string, mixed>  $overrides
 */
function unpaidLevelData(string $class, array $overrides = []): ToolsUnpaidLevelNotificationData
{
    return new $class(...array_merge(unpaidLevelPayload(), $overrides));
}

/** @return array<string, mixed> */
function unpaidLevelPayload(): array
{
    return [
        'statement_date' => '03/11/2026',
        'payment_due_date' => '08/11/2026',
        'invoices' => [
            ['number' => 'F-2026-001', 'amount' => '1.234,56 €', 'date' => '01/09/2026'],
            ['number' => 'F-2026-002', 'amount' => '200,00 €', 'date' => '01/10/2026'],
        ],
        'total' => '1.434,56 €',
        'beneficiary' => 'Trustup SA',
        'iban' => 'BE68539007547034',
        'bic' => 'GKCCBEBB',
        'communication' => '+++123/4567/89012+++',
        'qr_code_url' => 'https://example.test/qr.png',
        'invoices_url' => 'https://example.test/invoices',
    ];
}

it('reports the notification type of its level', function (string $class, NotificationType $type): void {
    expect(unpaidLevelData($class)->notificationType())->toBe($type);
})->with('unpaid levels');

it('carries every field in the serialized payload of its level', function (string $class): void {
    expect(unpaidLevelData($class)->toArray())->toBe(unpaidLevelPayload());
})->with('unpaid levels');

it('round-trips via toArray and fromArray on its own class', function (string $class): void {
    $original = unpaidLevelData($class);

    $rebuilt = $class::fromArray($original->toArray());

    expect($rebuilt)->toBeInstanceOf($class)
        ->and($rebuilt->toArray())->toBe($original->toArray());
})->with('unpaid levels');

it('hands every field to the email template unchanged', function (string $class): void {
    $data = unpaidLevelData($class);

    expect($data->toEmail()->variables)->toBe($data->toArray());
})->with('unpaid levels');

it('is sent by credit control under the TrustUp name', function (string $class): void {
    expect(unpaidLevelData($class)->emailSender())->toEqual(new EmailSender(SenderRole::CreditControl, 'TrustUp'));
})->with('unpaid levels');

it('uses the postmark template of its level', function (string $class, NotificationType $type, string $template): void {
    expect(unpaidLevelData($class)->emailTemplate())->toBe($template);
})->with('unpaid levels');

it('picks its postmark template by full locale, since copy differs per country', function (string $class): void {
    expect(unpaidLevelData($class)->emailTemplateLocaleGranularity())->toBe(EmailTemplateLocaleGranularity::Locale);
})->with('unpaid levels');

it('accepts a payload without bic and exposes it as null', function (string $class): void {
    $payload = unpaidLevelPayload();
    unset($payload['bic']);

    $data = $class::fromArray($payload);

    expect($data->bic)->toBeNull()
        ->and($data->toEmail()->variables['bic'])->toBeNull();
})->with('unpaid levels');

it('accepts an empty list of invoices as received', function (string $class): void {
    $payload = array_merge(unpaidLevelPayload(), ['invoices' => []]);

    expect($class::fromArray($payload)->invoices)->toBe([]);
})->with('unpaid levels');

it('fails loudly when a required field is missing', function (string $key): void {
    $payload = unpaidLevelPayload();
    unset($payload[$key]);

    expect(fn () => ToolsUnpaidLevel1NotificationData::fromArray($payload))->toThrow(InvalidNotificationDataException::class);
})->with(['statement_date', 'payment_due_date', 'invoices', 'total', 'beneficiary', 'iban', 'communication', 'qr_code_url', 'invoices_url']);
