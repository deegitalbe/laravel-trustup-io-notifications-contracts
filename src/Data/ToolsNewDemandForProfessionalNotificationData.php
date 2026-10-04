<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\EmailCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\PushCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersEmail;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersPush;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\SerializesFromConstructor;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

final readonly class ToolsNewDemandForProfessionalNotificationData implements EmailCapable, NotificationData, PushCapable
{
    use RendersEmail;
    use RendersPush;
    use SerializesFromConstructor;

    public function __construct(
        public string $base_url,
        public int $demand_id,
        public int $demand_professional_id,
        public string $workfield_slug,
        public ?string $city,
        public string $title,
        public string $description,
        public ?string $workfield_label = null,
        public ?string $first_name = null,
        public ?string $demand_type = null,
        public ?string $demand_source = null,
        public ?string $demand_illustration_url = null,
        public ?string $interested_url = null,
        public ?string $declined_url = null,
        public ?bool $is_direct = null,
        public ?bool $is_pro_website = null,
    ) {}

    public function notificationType(): NotificationType
    {
        return NotificationType::ToolsNewDemandForProfessionalNotification;
    }
}
