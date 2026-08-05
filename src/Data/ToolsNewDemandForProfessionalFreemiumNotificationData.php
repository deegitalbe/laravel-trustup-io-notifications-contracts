<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\EmailCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersEmail;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\SerializesFromConstructor;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

final readonly class ToolsNewDemandForProfessionalFreemiumNotificationData implements EmailCapable, NotificationData
{
    use RendersEmail;
    use SerializesFromConstructor;

    public function __construct(
        public string $base_url,
        public int $demand_id,
        public int $demand_professional_id,
        public string $workfield_slug,
        public ?string $city,
        public string $title,
        public string $description,
        public int $temporary_tenant_id,
        public string $claim_token,
    ) {}

    public function notificationType(): NotificationType
    {
        return NotificationType::ToolsNewDemandForProfessionalFreemiumNotification;
    }
}
