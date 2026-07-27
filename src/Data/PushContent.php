<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data;

use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidNotificationDataException;

readonly class PushContent
{
    /** @param array<string, mixed> $data */
    public function __construct(
        public string $title,
        public string $body,
        public array $data = [],
    ) {
        if ($this->title === '') {
            throw new InvalidNotificationDataException('PushContent title must not be empty.');
        }

        if ($this->body === '') {
            throw new InvalidNotificationDataException('PushContent body must not be empty.');
        }
    }
}
