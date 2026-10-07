<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsTestNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\ChannelEventKind;
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
        data: new ToolsTestNotificationData('https://example.test', 'Title', 'Body'),
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
        data: new ToolsTestNotificationData('https://example.test', 'Title', 'Body'),
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
        'data' => ['base_url' => 'https://example.test', 'title' => 'T', 'body' => 'B'],
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
        'data' => ['base_url' => 'https://example.test', 'title' => 'T', 'body' => 'B'],
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
        'data' => ['base_url' => 'https://example.test', 'title' => 'T', 'body' => 'B'],
    ];

    expect(fn () => StatusPayload::fromArray($invalidData))
        ->toThrow(UnknownNotificationTypeException::class);
});

/** @return array<string, mixed> */
function validStatusArray(): array
{
    return [
        'send_id' => 'send-abc',
        'channel' => NotificationChannel::Email->value,
        'status' => NotificationStatus::Error->value,
        'type' => NotificationType::ToolsTestNotification->value,
        'data' => ['base_url' => 'https://example.test', 'title' => 'T', 'body' => 'B'],
    ];
}

it('defaults the correlation fields to null when built without them', function (): void {
    $payload = new StatusPayload(
        sendId: 'send-abc',
        channel: NotificationChannel::Email,
        status: NotificationStatus::Pending,
        type: NotificationType::ToolsTestNotification,
        data: new ToolsTestNotificationData('https://example.test', 'Title', 'Body'),
    );

    expect($payload->eventId)->toBeNull()
        ->and($payload->sendEventId)->toBeNull()
        ->and($payload->occurredAt)->toBeNull()
        ->and($payload->kind)->toBeNull()
        ->and($payload->failureReason)->toBeNull();
});

it('round-trips the correlation fields through toArray and fromArray', function (): void {
    $original = new StatusPayload(
        sendId: 'send-abc',
        channel: NotificationChannel::Email,
        status: NotificationStatus::Error,
        type: NotificationType::ToolsTestNotification,
        data: new ToolsTestNotificationData('https://example.test', 'Title', 'Body'),
        eventId: 'evt-1',
        sendEventId: '42',
        occurredAt: '2026-08-21T10:00:00+00:00',
        kind: ChannelEventKind::SpamComplaint,
        failureReason: 'recipient complained',
    );

    $array = $original->toArray();

    expect($array)->toMatchArray([
        'event_id' => 'evt-1',
        'send_event_id' => '42',
        'occurred_at' => '2026-08-21T10:00:00+00:00',
        'kind' => 'spam_complaint',
        'failure_reason' => 'recipient complained',
    ]);

    $restored = StatusPayload::fromArray($array);

    expect($restored->eventId)->toBe('evt-1')
        ->and($restored->sendEventId)->toBe('42')
        ->and($restored->occurredAt)->toBe('2026-08-21T10:00:00+00:00')
        ->and($restored->kind)->toBe(ChannelEventKind::SpamComplaint)
        ->and($restored->failureReason)->toBe('recipient complained');
});

it('serializes absent correlation fields as null', function (): void {
    $array = StatusPayload::fromArray(validStatusArray())->toArray();

    expect($array)->toMatchArray([
        'event_id' => null,
        'send_event_id' => null,
        'occurred_at' => null,
        'kind' => null,
        'failure_reason' => null,
    ]);
});

it('decodes a message produced before the correlation fields existed', function (): void {
    $payload = StatusPayload::fromArray(validStatusArray());

    expect($payload->sendId)->toBe('send-abc')
        ->and($payload->status)->toBe(NotificationStatus::Error)
        ->and($payload->eventId)->toBeNull()
        ->and($payload->sendEventId)->toBeNull()
        ->and($payload->occurredAt)->toBeNull()
        ->and($payload->kind)->toBeNull()
        ->and($payload->failureReason)->toBeNull();
});

it('decodes an unknown kind as null instead of throwing', function (): void {
    $data = validStatusArray();
    $data['kind'] = 'exploded';

    expect(StatusPayload::fromArray($data)->kind)->toBeNull();
});
