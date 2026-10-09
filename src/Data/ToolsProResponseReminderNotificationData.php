<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\EmailCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersEmail;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\SerializesFromConstructor;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

final readonly class ToolsProResponseReminderNotificationData implements EmailCapable, NotificationData
{
    use RendersEmail;
    use SerializesFromConstructor;

    public function __construct(
        public string $base_url,
        public int $demand_id,
        public int $demand_professional_id,
        public string $title,
        public ?string $workfield_slug = null,
        public ?string $city = null,
        public ?string $demand_type = null,
        public ?string $demand_illustration_url = null,
        public ?string $interested_url = null,
        public ?string $declined_url = null,
        public ?bool $is_direct = null,
        public ?string $description = null,
    ) {}

    public function notificationType(): NotificationType
    {
        return NotificationType::ToolsProResponseReminderNotification;
    }
}
