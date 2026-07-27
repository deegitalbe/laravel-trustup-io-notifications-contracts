<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Enums;

enum ChannelEventKind: string
{
    case Delivered = 'delivered';
    case Bounced = 'bounced';
    case SpamComplaint = 'spam_complaint';
    case Opened = 'opened';
    case Clicked = 'clicked';
    case Rejected = 'rejected';
    case Undeliverable = 'undeliverable';

    public function canonicalStatus(): NotificationStatus
    {
        return match ($this) {
            self::Delivered => NotificationStatus::Delivered,
            self::Bounced, self::SpamComplaint, self::Rejected, self::Undeliverable => NotificationStatus::Error,
            self::Opened, self::Clicked => NotificationStatus::Sent, // @phpstan-ignore-line
            default => throw new \LogicException("ChannelEventKind [{$this->value}] has no canonicalStatus mapping."),
        };
    }

    public function isEngagement(): bool
    {
        return match ($this) {
            self::Opened, self::Clicked => true,
            self::Delivered, self::Bounced, self::SpamComplaint, self::Rejected, self::Undeliverable => false, // @phpstan-ignore-line
            default => throw new \LogicException("ChannelEventKind [{$this->value}] has no isEngagement mapping."),
        };
    }
}
