<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsTestNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationChannel;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationStatus;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidEnvelopeException;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\UnknownNotificationTypeException;
use Deegitalbe\TrustupIoNotificationsContracts\Status\StatusPayload;

it('builds status payload with all required fields', function (): void {
    $payload = new StatusPayload(
        sendId: 'send-abc-123',
        channel: NotificationChannel::Email,
        status: NotificationStatus::Pending,
        type: NotificationType::ToolsTestNotification,
        data: new ToolsTestNotificationData('Title', 'Body'),
    );

    expect($payload->sendId)->toBe('send-abc-123');
    expect($payload->channel)->toBe(NotificationChannel::Email);
    expect($payload->status)->toBe(NotificationStatus::Pending);
});

it('round-trips status payload via toArray and fromArray', function (): void {
    $original = new StatusPayload(
        sendId: 'send-abc-123',
        channel: NotificationChannel::Email,
        status: NotificationStatus::Pending,
        type: NotificationType::ToolsTestNotification,
        data: new ToolsTestNotificationData('Title', 'Body'),
    );
    $restored = StatusPayload::fromArray($original->toArray());

    expect($restored->sendId)->toBe($original->sendId);
    expect($restored->channel)->toBe($original->channel);
    expect($restored->status)->toBe($original->status);
    expect($restored->type)->toBe($original->type);
    expect($restored->data->title)->toBe('Title');
});

it('throws InvalidEnvelopeException when status value is not in enum', function (): void {
    $invalidData = [
        'send_id' => 'send-abc',
        'channel' => NotificationChannel::Email->value,
        'status' => 'unknown-status',
        'type' => NotificationType::ToolsTestNotification->value,
        'data' => ['title' => 'T', 'body' => 'B'],
    ];

    expect(fn () => StatusPayload::fromArray($invalidData))
        ->toThrow(InvalidEnvelopeException::class);
});

it('throws InvalidEnvelopeException when channel value is not in enum in fromArray', function (): void {
    $invalidData = [
        'send_id' => 'send-abc',
        'channel' => 'unknown-channel',
        'status' => NotificationStatus::Pending->value,
        'type' => NotificationType::ToolsTestNotification->value,
        'data' => ['title' => 'T', 'body' => 'B'],
    ];

    expect(fn () => StatusPayload::fromArray($invalidData))
        ->toThrow(InvalidEnvelopeException::class);
});

it('throws UnknownNotificationTypeException when type is unknown in status fromArray', function (): void {
    $invalidData = [
        'send_id' => 'send-abc',
        'channel' => NotificationChannel::Email->value,
        'status' => NotificationStatus::Pending->value,
        'type' => 'unknown.type',
        'data' => ['title' => 'T', 'body' => 'B'],
    ];

    expect(fn () => StatusPayload::fromArray($invalidData))
        ->toThrow(UnknownNotificationTypeException::class);
});
