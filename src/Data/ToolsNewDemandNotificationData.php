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

final readonly class ToolsNewDemandNotificationData implements EmailCapable, NotificationData, PushCapable
{
    use RendersEmail;
    use RendersPush;
    use SerializesFromConstructor;

    public function __construct(
        public string $base_url,
        public string $first_name,
        public string $workfield_name,
        public int $demand_professional_id,
    ) {}

    public function notificationType(): NotificationType
    {
        return NotificationType::ToolsNewDemandNotification;
    }
}
