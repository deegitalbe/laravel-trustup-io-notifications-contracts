<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsTestNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\EventDirection;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationChannel;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationStatus;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\Source;
use Deegitalbe\TrustupIoNotificationsContracts\Envelope;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidEnvelopeException;
use Deegitalbe\TrustupIoNotificationsContracts\Request\Recipient;
use Deegitalbe\TrustupIoNotificationsContracts\Request\RequestPayload;
use Deegitalbe\TrustupIoNotificationsContracts\Serialization\EnvelopeSerializer;
use Deegitalbe\TrustupIoNotificationsContracts\Status\StatusPayload;

it('round-trips a request envelope via EnvelopeSerializer encode and decode', function (): void {
    $payload = new RequestPayload(
        type: NotificationType::ToolsTestNotification,
        recipient: Recipient::identified('ext-1', Source::Tools),
        data: new ToolsTestNotificationData('Hello', 'World'),
        channels: [NotificationChannel::Email, NotificationChannel::Sms],
    );
    $envelope = new Envelope(Envelope::CURRENT_VERSION, EventDirection::Request, $payload);

    $serializer = new EnvelopeSerializer;
    $decoded = $serializer->decode($serializer->encode($envelope));

    expect($decoded->version)->toBe($envelope->version);
    expect($decoded->direction)->toBe($envelope->direction);
    expect($decoded->payload)->toBeInstanceOf(RequestPayload::class);
    expect($decoded->payload->type)->toBe(NotificationType::ToolsTestNotification);
    expect($decoded->payload->channels)->toBe([NotificationChannel::Email, NotificationChannel::Sms]);
});

it('round-trips a status envelope via EnvelopeSerializer encode and decode', function (): void {
    $payload = new StatusPayload(
        sendId: 'send-abc',
        channel: NotificationChannel::Email,
        status: NotificationStatus::Pending,
        type: NotificationType::ToolsTestNotification,
        data: new ToolsTestNotificationData('Hello', 'World'),
    );
    $envelope = new Envelope(Envelope::CURRENT_VERSION, EventDirection::Status, $payload);

    $serializer = new EnvelopeSerializer;
    $decoded = $serializer->decode($serializer->encode($envelope));

    expect($decoded->direction)->toBe(EventDirection::Status);
    expect($decoded->payload)->toBeInstanceOf(StatusPayload::class);
    expect($decoded->payload->sendId)->toBe('send-abc');
});

it('throws InvalidEnvelopeException when version key is absent', function (): void {
    $data = [
        'direction' => 'request',
        'payload' => [],
    ];

    expect(fn () => (new EnvelopeSerializer)->decode($data))
        ->toThrow(InvalidEnvelopeException::class);
});

it('throws InvalidEnvelopeException when direction is not a known EventDirection', function (): void {
    $data = [
        'version' => Envelope::CURRENT_VERSION,
        'direction' => 'unknown-direction',
        'payload' => [],
    ];

    expect(fn () => (new EnvelopeSerializer)->decode($data))
        ->toThrow(InvalidEnvelopeException::class);
});

it('throws InvalidEnvelopeException when required keys are missing', function (): void {
    $data = [
        'version' => Envelope::CURRENT_VERSION,
        'direction' => 'request',
    ];

    expect(fn () => (new EnvelopeSerializer)->decode($data))
        ->toThrow(InvalidEnvelopeException::class);
});
