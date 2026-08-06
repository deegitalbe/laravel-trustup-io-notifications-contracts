<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Exceptions;

use RuntimeException;

class MissingKafkaCredentialsException extends RuntimeException
{
    public function __construct(string $securityProtocol)
    {
        parent::__construct(
            "Kafka security protocol [{$securityProtocol}] requires SASL credentials, but one of TRUSTUP_IO_NOTIFICATIONS_KAFKA_USERNAME, TRUSTUP_IO_NOTIFICATIONS_KAFKA_PASSWORD or TRUSTUP_IO_NOTIFICATIONS_KAFKA_MECHANISMS is not set."
        );
    }
}
