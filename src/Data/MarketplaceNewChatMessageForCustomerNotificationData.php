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

    public function __construct(
        public string $base_url,
        public int $demand_id,
        public string $locale,
        public ?string $claim_token = null,
        public ?string $cometchat_group_guid = null,
        public ?string $pro_name = null,
        public ?string $pro_phone = null,
        public ?string $pro_email = null,
        public ?string $pro_logo = null,
    ) {}

    public function notificationType(): NotificationType
    {
        return NotificationType::MarketplaceNewChatMessageForCustomerNotification;
    }

    /** @return array<string, mixed> */
    protected function emailVariables(): array
    {
        return [...$this->toArray(), 'has_pro_logo' => $this->pro_logo !== null];
    }
}
