<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\EmailCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersEmail;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\SerializesFromConstructor;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\EmailTemplateLocaleGranularity;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

/**
 * Test notification data class that opts into full-locale granularity.
 * Used to verify the Locale suffix path end-to-end in the job.
 */
final readonly class ToolsFullLocaleTestNotificationData implements EmailCapable, NotificationData
{
    use RendersEmail;
    use SerializesFromConstructor;

    public function __construct(
        public string $title,
        public string $body,
    ) {}

    public function notificationType(): NotificationType
    {
        return NotificationType::ToolsFullLocaleTestNotification;
    }

    public function emailTemplateLocaleGranularity(): EmailTemplateLocaleGranularity
    {
        return EmailTemplateLocaleGranularity::Locale;
    }
}
