<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsTestNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\EventDirection;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\Source;
use Deegitalbe\TrustupIoNotificationsContracts\Envelope;
use Deegitalbe\TrustupIoNotificationsContracts\Request\Recipient;
use Deegitalbe\TrustupIoNotificationsContracts\Request\RequestPayload;
use Deegitalbe\TrustupIoNotificationsContracts\Serialization\EnvelopeSerializer;

function makeRequestEnvelope(?string $eventId = null): Envelope
{
    $payload = new RequestPayload(
        type: NotificationType::ToolsTestNotification,
        recipient: Recipient::identified('ext-1', Source::Tools),
        data: new ToolsTestNotificationData('https://example.test', 'Hello', 'World'),
        channels: null,
    );

    return new Envelope(Envelope::CURRENT_VERSION, EventDirection::Request, $payload, $eventId);
}

it('encodes and decodes an envelope with an event_id', function (): void {
    $serializer = new EnvelopeSerializer;
    $envelope = makeRequestEnvelope('evt-uuid-1234');

    $decoded = $serializer->decode($serializer->encode($envelope));

    expect($decoded->eventId)->toBe('evt-uuid-1234');
});

it('yields null event_id when the key is absent from the encoded array', function (): void {
    $serializer = new EnvelopeSerializer;
    $data = [
        'version' => Envelope::CURRENT_VERSION,
        'direction' => 'request',
        'payload' => [
            'type' => 'tools.test.notification',
            'recipient' => ['source' => 'tools', 'external_user_id' => 'ext-1'],
            'data' => ['base_url' => 'https://example.test', 'title' => 'T', 'body' => 'B'],
            'channels' => null,
        ],
    ];

    $decoded = $serializer->decode($data);

    expect($decoded->eventId)->toBeNull();
});

it('encodes and decodes an envelope with no event_id (null round-trips)', function (): void {
    $serializer = new EnvelopeSerializer;
    $envelope = makeRequestEnvelope(null);

    $decoded = $serializer->decode($serializer->encode($envelope));

    expect($decoded->eventId)->toBeNull();
});
