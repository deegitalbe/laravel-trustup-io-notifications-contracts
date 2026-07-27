<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Request;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\Serializable;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationChannel;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidEnvelopeException;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\UnknownNotificationTypeException;

readonly class RequestPayload implements Serializable
{
    /** @param list<NotificationChannel>|null $channels */
    public function __construct(
        public NotificationType $type,
        public Recipient $recipient,
        public NotificationData $data,
        public ?array $channels,
    ) {
        if ($this->channels !== null && $this->channels === []) {
            throw new InvalidEnvelopeException('Request payload channels must not be an empty list. Provide at least one channel or null to mean all channels.');
        }
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'type' => $this->type->value,
            'recipient' => $this->recipient->toArray(),
            'data' => $this->data->toArray(),
            'channels' => $this->channels !== null
                ? array_map(fn (NotificationChannel $channel) => $channel->value, $this->channels)
                : null,
        ];
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        $typeValue = $data['type'] ?? null;
        $type = NotificationType::tryFrom((string) $typeValue);

        if ($type === null) {
            throw new UnknownNotificationTypeException("Unknown notification type [{$typeValue}].");
        }

        $dataClass = $type->dataClass();
        /** @var NotificationData $notificationData */
        $notificationData = $dataClass::fromArray((array) ($data['data'] ?? []));

        $rawChannels = $data['channels'] ?? null;
        $channels = null;

        if ($rawChannels !== null) {
            if ($rawChannels === []) {
                throw new InvalidEnvelopeException('Request payload channels must not be an empty list.');
            }

            $channels = array_map(
                fn (mixed $value) => NotificationChannel::from((string) $value),
                (array) $rawChannels,
            );
        }

        return new self(
            type: $type,
            recipient: Recipient::fromArray((array) ($data['recipient'] ?? [])),
            data: $notificationData,
            channels: $channels,
        );
    }
}
