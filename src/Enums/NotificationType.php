<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Enums;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\EmailCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\PushCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\SmsCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsCommentNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsFullLocaleTestNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsTestNotificationData;
use LogicException;

enum NotificationType: string
{
    case ToolsTestNotification = 'tools.test.notification';

    case ToolsCommentNotification = 'tools.comment.notification';

    /** Test fixture that opts into full-locale granularity for email templates. */
    case ToolsFullLocaleTestNotification = 'tools.full-locale.test.notification';

    /**
     * Dotless form of the value ("tools-test-notification"), safe to embed in a
     * dotted translation key or a provider template name.
     */
    public function slug(): string
    {
        return str_replace('.', '-', $this->value);
    }

    public function dataClass(): string
    {
        return match ($this) {
            self::ToolsTestNotification => ToolsTestNotificationData::class,
            self::ToolsCommentNotification => ToolsCommentNotificationData::class,
            self::ToolsFullLocaleTestNotification => ToolsFullLocaleTestNotificationData::class, // @phpstan-ignore-line
            default => throw new LogicException("NotificationType [{$this->value}] has no dataClass mapping."),
        };
    }

    public function source(): Source
    {
        return match ($this) {
            self::ToolsTestNotification => Source::Tools,
            self::ToolsCommentNotification => Source::Tools,
            self::ToolsFullLocaleTestNotification => Source::Tools, // @phpstan-ignore-line
            default => throw new LogicException("NotificationType [{$this->value}] has no source mapping."),
        };
    }

    /** @return array<int, NotificationChannel> */
    public function supportedChannels(): array
    {
        $dataClass = $this->dataClass();
        $channels = [];

        if (is_a($dataClass, EmailCapable::class, true)) {
            $channels[] = NotificationChannel::Email;
        }

        if (is_a($dataClass, SmsCapable::class, true)) {
            $channels[] = NotificationChannel::Sms;
        }

        if (is_a($dataClass, PushCapable::class, true)) {
            $channels[] = NotificationChannel::Push;
        }

        return $channels;
    }

    /** @return array<int, self> */
    public static function forSource(Source $source): array
    {
        return array_values(array_filter(
            self::cases(),
            fn (self $type): bool => $type->source() === $source,
        ));
    }
}
