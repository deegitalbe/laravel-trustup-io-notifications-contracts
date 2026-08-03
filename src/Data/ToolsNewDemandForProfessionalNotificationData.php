<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\EmailCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersEmail;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\SerializesFromConstructor;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

final readonly class ToolsNewDemandForProfessionalNotificationData implements EmailCapable, NotificationData
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
    ) {}

    public function notificationType(): NotificationType
    {
        return NotificationType::ToolsNewDemandForProfessionalNotification;
    }
}
