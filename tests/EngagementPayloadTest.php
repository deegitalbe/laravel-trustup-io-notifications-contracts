<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Engagement\EngagementPayload;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\ChannelEventKind;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\EventDirection;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationChannel;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Envelope;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidEnvelopeException;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\UnknownNotificationTypeException;
use Deegitalbe\TrustupIoNotificationsContracts\Serialization\EnvelopeSerializer;

/** @return array<string, mixed> */
function validEngagementArray(): array
{
    return [
        'send_id' => 'send-1',
        'channel' => 'email',
        'kind' => 'clicked',
        'type' => NotificationType::ToolsTestNotification->value,
        'data' => ['base_url' => 'https://example.test', 'title' => 'T', 'body' => 'B'],
        'clicked_url' => 'https://example.com',
    ];
}

it('round-trips an EngagementPayload through toArray and fromArray', function (): void {
    $payload = EngagementPayload::fromArray(validEngagementArray());

    expect($payload->sendId)->toBe('send-1')
        ->and($payload->channel)->toBe(NotificationChannel::Email)
        ->and($payload->kind)->toBe(ChannelEventKind::Clicked)
        ->and($payload->type)->toBe(NotificationType::ToolsTestNotification)
        ->and($payload->clickedUrl)->toBe('https://example.com')
        ->and($payload->toArray())->toBe(validEngagementArray() + [
            'event_id' => null,
            'send_event_id' => null,
            'occurred_at' => null,
        ]);
});

it('defaults clicked_url to null when absent', function (): void {
    $data = validEngagementArray();
    unset($data['clicked_url']);

    expect(EngagementPayload::fromArray($data)->clickedUrl)->toBeNull();
});

it('throws on an invalid channel', function (): void {
    $data = validEngagementArray();
    $data['channel'] = 'fax';

    expect(fn () => EngagementPayload::fromArray($data))->toThrow(InvalidEnvelopeException::class);
});

it('throws on an invalid channel event kind', function (): void {
    $data = validEngagementArray();
    $data['kind'] = 'exploded';

    expect(fn () => EngagementPayload::fromArray($data))->toThrow(InvalidEnvelopeException::class);
});

it('throws on an unknown notification type', function (): void {
    $data = validEngagementArray();
    $data['type'] = 'unknown.type';

    expect(fn () => EngagementPayload::fromArray($data))->toThrow(UnknownNotificationTypeException::class);
});

it('is decoded by EnvelopeSerializer for the engagement direction', function (): void {
    $serializer = new EnvelopeSerializer;

    $envelope = $serializer->decode([
        'version' => Envelope::CURRENT_VERSION,
        'direction' => EventDirection::Engagement->value,
        'event_id' => 'evt-1',
        'payload' => validEngagementArray(),
    ]);

    expect($envelope->direction)->toBe(EventDirection::Engagement)
        ->and($envelope->payload)->toBeInstanceOf(EngagementPayload::class)
        ->and($serializer->encode($envelope)['payload'])->toBe(validEngagementArray() + [
            'event_id' => null,
            'send_event_id' => null,
            'occurred_at' => null,
        ]);
});

it('defaults the correlation fields to null when absent', function (): void {
    $payload = EngagementPayload::fromArray(validEngagementArray());

    expect($payload->eventId)->toBeNull()
        ->and($payload->sendEventId)->toBeNull()
        ->and($payload->occurredAt)->toBeNull();
});

it('serializes absent correlation fields as null', function (): void {
    expect(EngagementPayload::fromArray(validEngagementArray())->toArray())->toMatchArray([
        'event_id' => null,
        'send_event_id' => null,
        'occurred_at' => null,
    ]);
});

it('round-trips the correlation fields through toArray and fromArray', function (): void {
    $data = validEngagementArray() + [
        'event_id' => 'evt-1',
        'send_event_id' => '42',
        'occurred_at' => '2026-08-21T10:00:00+00:00',
    ];

    $payload = EngagementPayload::fromArray($data);

    expect($payload->eventId)->toBe('evt-1')
        ->and($payload->sendEventId)->toBe('42')
        ->and($payload->occurredAt)->toBe('2026-08-21T10:00:00+00:00')
        ->and($payload->toArray())->toBe($data);
});
