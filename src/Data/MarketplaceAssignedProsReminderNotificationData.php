<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\EmailCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersEmail;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\SerializesFromConstructor;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

final readonly class MarketplaceAssignedProsReminderNotificationData implements EmailCapable, NotificationData
{
    use RendersEmail;
    use SerializesFromConstructor;

    /**
     * @param  array<int, array{name?: string|null, logo?: string|null, trust_score?: string|null, trust_score_label?: string|null, conversation_url?: string|null, select_url?: string|null, call_url?: string|null}>|null  $pros
     */
    public function __construct(
        public string $base_url,
        public int $demand_id,
        public ?string $claim_token = null,
        public ?array $pros = null,
        public ?string $demand_description = null,
        public ?string $demand_cancel_url = null,
        public ?string $demand_illustration_url = null,
        public ?int $professional_count = null,
        public ?string $workfield_label = null,
        public ?string $city = null,
        public ?bool $single_professional = null,
    ) {}

    public function notificationType(): NotificationType
    {
        return NotificationType::MarketplaceAssignedProsReminderNotification;
    }
}
