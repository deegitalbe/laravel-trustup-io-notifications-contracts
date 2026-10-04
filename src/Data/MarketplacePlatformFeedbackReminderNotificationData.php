<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\EmailCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersEmail;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\SerializesFromConstructor;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

final readonly class MarketplacePlatformFeedbackReminderNotificationData implements EmailCapable, NotificationData
{
    use RendersEmail;
    use SerializesFromConstructor;

    /**
     * @param  array<int, array{note: int, url: string}>|null  $rating_links
     */
    public function __construct(
        public string $base_url,
        public int $demand_id,
        public ?string $first_name = null,
        public ?string $pro_name = null,
        public ?array $rating_links = null,
    ) {}

    public function notificationType(): NotificationType
    {
        return NotificationType::MarketplacePlatformFeedbackReminderNotification;
    }
}
