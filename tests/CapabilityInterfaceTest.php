<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\EmailCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\PushCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\SmsCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsTestNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Illuminate\Support\Facades\Lang;

beforeEach(function (): void {
    $key = NotificationType::ToolsTestNotification->slug();
    Lang::addLines([
        "notifications.{$key}.sms" => 'SMS :body',
        "notifications.{$key}.push.title" => 'Push :title',
        "notifications.{$key}.push.body" => 'Push :body',
    ], 'en');
    app()->setLocale('en');
});

it('ToolsTestNotificationData implements EmailCapable', function (): void {
    $data = new ToolsTestNotificationData('Title', 'Body');

    expect($data)->toBeInstanceOf(EmailCapable::class);
});

it('ToolsTestNotificationData implements SmsCapable', function (): void {
    $data = new ToolsTestNotificationData('Title', 'Body');

    expect($data)->toBeInstanceOf(SmsCapable::class);
});

it('ToolsTestNotificationData implements PushCapable', function (): void {
    $data = new ToolsTestNotificationData('Title', 'Body');

    expect($data)->toBeInstanceOf(PushCapable::class);
});

it('toEmail returns EmailContent with title and body as variables', function (): void {
    $data = new ToolsTestNotificationData('Hello', 'World');

    $emailContent = $data->toEmail();

    expect($emailContent->variables)->toBe(['title' => 'Hello', 'body' => 'World']);
});

it('toSms returns SmsContent rendered from the notification type translation key', function (): void {
    $data = new ToolsTestNotificationData('Title', 'SMS body text');

    $smsContent = $data->toSms();

    expect($smsContent->body)->toBe('SMS SMS body text');
});

it('toPush returns PushContent rendered from translation keys with constructor data', function (): void {
    $data = new ToolsTestNotificationData('Push Title', 'Push Body');

    $pushContent = $data->toPush();

    expect($pushContent->title)->toBe('Push Push Title')
        ->and($pushContent->body)->toBe('Push Push Body')
        ->and($pushContent->data)->toBe(['title' => 'Push Title', 'body' => 'Push Body']);
});

it('toArray and fromArray round-trip preserves title and body', function (): void {
    $original = new ToolsTestNotificationData('My Title', 'My Body');
    $restored = ToolsTestNotificationData::fromArray($original->toArray());

    expect($restored->title)->toBe($original->title)
        ->and($restored->body)->toBe($original->body);
});
