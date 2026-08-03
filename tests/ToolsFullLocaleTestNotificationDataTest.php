<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsFullLocaleTestNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\EmailTemplateLocaleGranularity;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

it('opts into full-locale granularity for its email template', function (): void {
    $data = new ToolsFullLocaleTestNotificationData('https://example.test', 'Title', 'Body');

    expect($data->emailTemplateLocaleGranularity())->toBe(EmailTemplateLocaleGranularity::Locale)
        ->and($data->notificationType())->toBe(NotificationType::ToolsFullLocaleTestNotification);
});
