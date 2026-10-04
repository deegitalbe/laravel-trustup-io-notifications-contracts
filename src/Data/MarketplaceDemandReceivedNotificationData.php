<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\EmailCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersEmail;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\SerializesFromConstructor;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

final readonly class MarketplaceDemandReceivedNotificationData implements EmailCapable, NotificationData
{
    use RendersEmail;
    use SerializesFromConstructor;

    public function __construct(
        public string $base_url,
        public int $demand_id,
        public ?string $ai_session_id = null,
        public ?string $claim_token = null,
        public ?string $first_name = null,
        public ?bool $email_already_known = null,
        public ?string $demand_type = null,
        public ?string $pro_name = null,
        public ?string $demand_description = null,
        public ?string $demand_cancel_url = null,
        public ?string $demand_illustration_url = null,
        public ?bool $is_direct = null,
    ) {}

    public function notificationType(): NotificationType
    {
        return NotificationType::MarketplaceDemandReceivedNotification;
    }
}
