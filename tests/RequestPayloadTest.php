<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsTestNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationChannel;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\Source;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidEnvelopeException;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\UnknownNotificationTypeException;
use Deegitalbe\TrustupIoNotificationsContracts\Request\Recipient;
use Deegitalbe\TrustupIoNotificationsContracts\Request\RequestPayload;

it('builds request payload with type, recipient, data and channels', function (): void {
    $payload = new RequestPayload(
        type: NotificationType::ToolsTestNotification,
        recipient: Recipient::identified('ext-1', Source::Tools),
        data: new ToolsTestNotificationData('https://example.test', 'Title', 'Body'),
        channels: [NotificationChannel::Email, NotificationChannel::Sms],
    );

    expect($payload->type)->toBe(NotificationType::ToolsTestNotification);
    expect($payload->channels)->toBe([NotificationChannel::Email, NotificationChannel::Sms]);
});

it('round-trips request payload with channels via toArray and fromArray', function (): void {
    $original = new RequestPayload(
        type: NotificationType::ToolsTestNotification,
        recipient: Recipient::identified('ext-1', Source::Tools),
        data: new ToolsTestNotificationData('https://example.test', 'Title', 'Body'),
        channels: [NotificationChannel::Email, NotificationChannel::Sms],
    );
    $restored = RequestPayload::fromArray($original->toArray());

    expect($restored->type)->toBe($original->type);
    expect($restored->channels)->toBe($original->channels);
    expect($restored->data->title)->toBe('Title');
});

it('accepts null channels to mean all channels', function (): void {
    $payload = new RequestPayload(
        type: NotificationType::ToolsTestNotification,
        recipient: Recipient::identified('ext-1', Source::Tools),
        data: new ToolsTestNotificationData('https://example.test', 'Title', 'Body'),
        channels: null,
    );

    expect($payload->channels)->toBeNull();
});

it('throws InvalidEnvelopeException when channels is empty array', function (): void {
    expect(fn () => new RequestPayload(
        type: NotificationType::ToolsTestNotification,
        recipient: Recipient::identified('ext-1', Source::Tools),
        data: new ToolsTestNotificationData('https://example.test', 'Title', 'Body'),
        channels: [],
    ))->toThrow(InvalidEnvelopeException::class);
});

it('throws UnknownNotificationTypeException when type is unknown in fromArray', function (): void {
    $invalidData = [
        'type' => 'unknown.type',
        'recipient' => Recipient::identified('ext-1', Source::Tools)->toArray(),
        'data' => ['base_url' => 'https://example.test', 'title' => 'T', 'body' => 'B'],
        'channels' => null,
    ];

    expect(fn () => RequestPayload::fromArray($invalidData))
        ->toThrow(UnknownNotificationTypeException::class);
});

it('throws InvalidEnvelopeException when channels is empty array in fromArray', function (): void {
    $invalidData = [
        'type' => NotificationType::ToolsTestNotification->value,
        'recipient' => Recipient::identified('ext-1', Source::Tools)->toArray(),
        'data' => ['base_url' => 'https://example.test', 'title' => 'T', 'body' => 'B'],
        'channels' => [],
    ];

    expect(fn () => RequestPayload::fromArray($invalidData))
        ->toThrow(InvalidEnvelopeException::class);
});
