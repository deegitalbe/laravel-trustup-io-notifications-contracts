<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\EmailCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersEmail;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\SerializesFromConstructor;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

final readonly class ToolsCommentNotificationData implements EmailCapable, NotificationData
{
    use RendersEmail;
    use SerializesFromConstructor;

    /** @param list<array<string, string>> $attachment_details */
    public function __construct(
        public string $product_url,
        public string $product_name,
        public string $body,
        public array $attachment_details,
        public string $commenter_name,
        public string $timestamp,
        public string $action_url,
        public string $notifications_url,
        public string $company_name,
        public string $company_address,
    ) {}

    public function notificationType(): NotificationType
    {
        return NotificationType::ToolsCommentNotification;
    }
}
