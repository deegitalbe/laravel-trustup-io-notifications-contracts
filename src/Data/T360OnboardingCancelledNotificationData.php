<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data;

use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

final readonly class T360OnboardingCancelledNotificationData extends T360OnboardingNotificationData
{
    public function notificationType(): NotificationType
    {
        return NotificationType::T360OnboardingCancelledNotification;
    }
}
