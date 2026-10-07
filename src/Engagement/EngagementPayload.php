<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Engagement;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\Serializable;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\ChannelEventKind;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationChannel;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidEnvelopeException;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\UnknownNotificationTypeException;

final readonly class EngagementPayload implements Serializable
{
    public function __construct(
        public string $sendId,
        public NotificationChannel $channel,
        public ChannelEventKind $kind,
        public NotificationType $type,
        public NotificationData $data,
        public ?string $clickedUrl,
        public ?string $eventId = null,
        public ?string $sendEventId = null,
        public ?string $occurredAt = null,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'send_id' => $this->sendId,
            'channel' => $this->channel->value,
            'kind' => $this->kind->value,
            'type' => $this->type->value,
            'data' => $this->data->toArray(),
            'clicked_url' => $this->clickedUrl,
            'event_id' => $this->eventId,
            'send_event_id' => $this->sendEventId,
            'occurred_at' => $this->occurredAt,
        ];
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): static
    {
        $channel = NotificationChannel::tryFrom((string) ($data['channel'] ?? ''));

        if ($channel === null) {
            throw new InvalidEnvelopeException("Invalid notification channel [{$data['channel']}].");
        }

        $kind = ChannelEventKind::tryFrom((string) ($data['kind'] ?? ''));

        if ($kind === null) {
            throw new InvalidEnvelopeException("Invalid channel event kind [{$data['kind']}].");
        }

        $typeValue = $data['type'] ?? null;
        $type = NotificationType::tryFrom((string) $typeValue);

        if ($type === null) {
            throw new UnknownNotificationTypeException("Unknown notification type [{$typeValue}].");
        }

        $dataClass = $type->dataClass();
        /** @var NotificationData $notificationData */
        $notificationData = $dataClass::fromArray((array) ($data['data'] ?? []));

        return new self(
            sendId: (string) ($data['send_id'] ?? ''),
            channel: $channel,
            kind: $kind,
            type: $type,
            data: $notificationData,
            clickedUrl: isset($data['clicked_url']) ? (string) $data['clicked_url'] : null,
            eventId: isset($data['event_id']) ? (string) $data['event_id'] : null,
            sendEventId: isset($data['send_event_id']) ? (string) $data['send_event_id'] : null,
            occurredAt: isset($data['occurred_at']) ? (string) $data['occurred_at'] : null,
        );
    }
}
