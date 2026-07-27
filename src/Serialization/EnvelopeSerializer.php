<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Serialization;

use Deegitalbe\TrustupIoNotificationsContracts\Engagement\EngagementPayload;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\EventDirection;
use Deegitalbe\TrustupIoNotificationsContracts\Envelope;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidEnvelopeException;
use Deegitalbe\TrustupIoNotificationsContracts\Request\RequestPayload;
use Deegitalbe\TrustupIoNotificationsContracts\Status\StatusPayload;

class EnvelopeSerializer
{
    /** @return array<string, mixed> */
    public function encode(Envelope $envelope): array
    {
        return [
            'version' => $envelope->version,
            'direction' => $envelope->direction->value,
            'event_id' => $envelope->eventId,
            'payload' => $envelope->payload->toArray(),
        ];
    }

    /** @param array<string, mixed> $data */
    public function decode(array $data): Envelope
    {
        if (! array_key_exists('version', $data)) {
            throw new InvalidEnvelopeException('Envelope is missing required field [version].');
        }

        if (! array_key_exists('payload', $data)) {
            throw new InvalidEnvelopeException('Envelope is missing required field [payload].');
        }

        $direction = EventDirection::tryFrom((string) ($data['direction'] ?? ''));

        if ($direction === null) {
            throw new InvalidEnvelopeException("Envelope direction [{$data['direction']}] is not a valid EventDirection. Must be one of: request, status, engagement.");
        }

        $version = (int) $data['version'];
        $payload = (array) $data['payload'];

        $resolvedPayload = match ($direction) {
            EventDirection::Request => RequestPayload::fromArray($payload),
            EventDirection::Status => StatusPayload::fromArray($payload),
            EventDirection::Engagement => EngagementPayload::fromArray($payload),
        };

        $eventId = isset($data['event_id']) ? (string) $data['event_id'] : null;

        return new Envelope($version, $direction, $resolvedPayload, $eventId);
    }
}
