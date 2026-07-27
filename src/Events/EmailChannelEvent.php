<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Events;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\ChannelEvent;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\ChannelEventKind;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationChannel;

final readonly class EmailChannelEvent implements ChannelEvent
{
    public function __construct(
        private string $sendId,
        private NotificationChannel $channel,
        private ChannelEventKind $kind,
        private string $occurredAt,
        private ?string $rawProviderCode,
        public ?string $clickedUrl,
        public ?string $bounceType,
    ) {}

    public function sendId(): string
    {
        return $this->sendId;
    }

    public function channel(): NotificationChannel
    {
        return $this->channel;
    }

    public function kind(): ChannelEventKind
    {
        return $this->kind;
    }

    public function occurredAt(): string
    {
        return $this->occurredAt;
    }

    public function rawProviderCode(): ?string
    {
        return $this->rawProviderCode;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'send_id' => $this->sendId,
            'channel' => $this->channel->value,
            'kind' => $this->kind->value,
            'occurred_at' => $this->occurredAt,
            'raw_provider_code' => $this->rawProviderCode,
            'clicked_url' => $this->clickedUrl,
            'bounce_type' => $this->bounceType,
        ];
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): static
    {
        return new self(
            sendId: (string) ($data['send_id'] ?? ''),
            channel: NotificationChannel::from((string) ($data['channel'] ?? '')),
            kind: ChannelEventKind::from((string) ($data['kind'] ?? '')),
            occurredAt: (string) ($data['occurred_at'] ?? ''),
            rawProviderCode: isset($data['raw_provider_code']) ? (string) $data['raw_provider_code'] : null,
            clickedUrl: isset($data['clicked_url']) ? (string) $data['clicked_url'] : null,
            bounceType: isset($data['bounce_type']) ? (string) $data['bounce_type'] : null,
        );
    }
}
