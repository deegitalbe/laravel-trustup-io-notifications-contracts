<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts;

use Deegitalbe\TrustupIoNotificationsContracts\Engagement\EngagementPayload;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\EventDirection;
use Deegitalbe\TrustupIoNotificationsContracts\Request\RequestPayload;
use Deegitalbe\TrustupIoNotificationsContracts\Status\StatusPayload;

readonly class Envelope
{
    public const int CURRENT_VERSION = 1;

    public function __construct(
        public int $version,
        public EventDirection $direction,
        public RequestPayload|StatusPayload|EngagementPayload $payload,
        public ?string $eventId = null,
    ) {}
}
