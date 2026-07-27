<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data;

use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidNotificationDataException;

readonly class SmsContent
{
    public function __construct(
        public string $body,
    ) {
        if ($this->body === '') {
            throw new InvalidNotificationDataException('SmsContent body must not be empty.');
        }
    }
}
