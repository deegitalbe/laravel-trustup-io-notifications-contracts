<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Status;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\Serializable;
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
        );
    }
}
