<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Contracts;

use Deegitalbe\TrustupIoNotificationsContracts\Enums\ChannelEventKind;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationChannel;

interface ChannelEvent extends Serializable
{
    public function sendId(): string;

    public function channel(): NotificationChannel;

    public function kind(): ChannelEventKind;

    public function occurredAt(): string;

    public function rawProviderCode(): ?string;
}
