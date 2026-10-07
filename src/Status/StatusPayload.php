<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Status;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\Serializable;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\ChannelEventKind;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationChannel;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationStatus;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidEnvelopeException;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\UnknownNotificationTypeException;

readonly class StatusPayload implements Serializable
{
    public function __construct(
        public string $sendId,
        public NotificationChannel $channel,
        public NotificationStatus $status,
        public NotificationType $type,
        public NotificationData $data,
        public ?string $eventId = null,
        public ?string $sendEventId = null,
        public ?string $occurredAt = null,
        public ?ChannelEventKind $kind = null,
        public ?string $failureReason = null,
    ) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'send_id' => $this->sendId,
            'channel' => $this->channel->value,
            'status' => $this->status->value,
            'type' => $this->type->value,
            'data' => $this->data->toArray(),
            'event_id' => $this->eventId,
            'send_event_id' => $this->sendEventId,
            'occurred_at' => $this->occurredAt,
            'kind' => $this->kind?->value,
            'failure_reason' => $this->failureReason,
        ];
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        $status = NotificationStatus::tryFrom((string) ($data['status'] ?? ''));

        if ($status === null) {
            throw new InvalidEnvelopeException("Invalid notification status [{$data['status']}]. Must be one of: pending, sent, delivered, error.");
        }

        $channel = NotificationChannel::tryFrom((string) ($data['channel'] ?? ''));

        if ($channel === null) {
            throw new InvalidEnvelopeException("Invalid notification channel [{$data['channel']}].");
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
            status: $status,
            type: $type,
            data: $notificationData,
            eventId: isset($data['event_id']) ? (string) $data['event_id'] : null,
            sendEventId: isset($data['send_event_id']) ? (string) $data['send_event_id'] : null,
            occurredAt: isset($data['occurred_at']) ? (string) $data['occurred_at'] : null,
            kind: ChannelEventKind::tryFrom((string) ($data['kind'] ?? '')),
            failureReason: isset($data['failure_reason']) ? (string) $data['failure_reason'] : null,
        );
    }
}
