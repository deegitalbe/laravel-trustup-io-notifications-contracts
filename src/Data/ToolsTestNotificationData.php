<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\EmailCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\PushCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\SmsCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersEmail;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersPush;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersSms;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\SerializesFromConstructor;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

final readonly class ToolsTestNotificationData implements EmailCapable, NotificationData, PushCapable, SmsCapable
{
    use RendersEmail;
    use RendersPush;
    use RendersSms;
    use SerializesFromConstructor;

    public function __construct(
        public string $base_url,
        public string $title,
        public string $body,
    ) {}

    public function notificationType(): NotificationType
    {
        return NotificationType::ToolsTestNotification;
    }
}
