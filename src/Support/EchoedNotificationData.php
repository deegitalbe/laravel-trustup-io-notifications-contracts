<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Support;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

/** Omitted bodies decode as empty strings so data classes requiring them still build. */
final class EchoedNotificationData
{
    /** @var list<string> */
    public const array OMITTED_KEYS = ['html_body', 'text_body'];

    /** @return array<string, mixed> */
    public static function toArray(NotificationData $data): array
    {
        return array_diff_key($data->toArray(), array_flip(self::OMITTED_KEYS));
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(NotificationType $type, array $data): NotificationData
    {
        $dataClass = $type->dataClass();

        /** @var NotificationData */
        return $dataClass::fromArray($data + array_fill_keys(self::OMITTED_KEYS, ''));
    }
}
