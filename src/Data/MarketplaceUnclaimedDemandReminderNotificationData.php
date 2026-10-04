<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\EmailCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersEmail;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\SerializesFromConstructor;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

final readonly class MarketplaceUnclaimedDemandReminderNotificationData implements EmailCapable, NotificationData
{
    use RendersEmail;
    use SerializesFromConstructor;

    public function __construct(
        public string $base_url,
        public int $demand_id,
        public string $title,
        public string $workfield_label,
        public ?string $ai_session_id = null,
        public ?string $claim_token = null,
        public ?string $demand_type = null,
        public ?string $demand_source = null,
        public ?string $pro_name = null,
        public ?string $pro_logo = null,
        public ?string $pro_phone = null,
        public ?string $pro_email = null,
        public ?string $demand_description = null,
        public ?string $demand_cancel_url = null,
        public ?string $demand_illustration_url = null,
        public ?bool $is_direct = null,
        public ?bool $is_pro_website = null,
    ) {}

    public function notificationType(): NotificationType
    {
        return NotificationType::MarketplaceUnclaimedDemandReminderNotification;
    }

    public function emailTemplate(): string
    {
        return $this->is_pro_website === true
            ? 'marketplace-whitelabel-unclaimed-demand-reminder-notification'
            : $this->notificationType()->slug();
    }

    /** @return array<string, mixed> */
    protected function emailVariables(): array
    {
        return [...$this->toArray(), 'has_pro_logo' => ($this->pro_logo ?? '') !== ''];
    }
}
