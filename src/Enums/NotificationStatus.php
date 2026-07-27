<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Enums;

enum NotificationStatus: string
{
    case Pending = 'pending';
    case Sent = 'sent';
    case Delivered = 'delivered';
    case Error = 'error';

    public function rank(): int
    {
        return match ($this) {
            self::Pending => 0,
            self::Sent => 1,
            self::Delivered => 2,
            self::Error => PHP_INT_MAX, // @phpstan-ignore-line
            default => throw new \LogicException("NotificationStatus [{$this->value}] has no rank mapping."),
        };
    }

    public function isError(): bool
    {
        return match ($this) {
            self::Error => true,
            self::Pending, self::Sent, self::Delivered => false, // @phpstan-ignore-line
            default => throw new \LogicException("NotificationStatus [{$this->value}] has no isError mapping."),
        };
    }
}
