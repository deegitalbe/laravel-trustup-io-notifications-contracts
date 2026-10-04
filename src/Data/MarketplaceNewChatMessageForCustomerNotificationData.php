<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\EmailCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersEmail;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\SerializesFromConstructor;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

final readonly class MarketplaceNewChatMessageForCustomerNotificationData implements EmailCapable, NotificationData
{
    use RendersEmail;
    use SerializesFromConstructor;

    /**
     * @param  array<int, array{note: int, url: string}>|null  $rating_links
     */
    public function __construct(
        public string $base_url,
        public int $demand_id,
        public string $locale,
        public ?string $claim_token = null,
        public ?string $cometchat_group_guid = null,
        public ?string $pro_name = null,
        public ?string $pro_logo = null,
        public ?string $pro_trust_score = null,
        public ?string $pro_trust_score_label = null,
        public ?string $workfield_label = null,
        public ?string $city = null,
        public ?string $demand_description = null,
        public ?string $demand_cancel_url = null,
        public ?string $demand_illustration_url = null,
        public ?array $rating_links = null,
    ) {}

    public function notificationType(): NotificationType
    {
        return NotificationType::MarketplaceNewChatMessageForCustomerNotification;
    }
}
