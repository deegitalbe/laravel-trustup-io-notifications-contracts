<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data;

use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

final readonly class ToolsUnpaidLevel2NotificationData extends ToolsUnpaidLevelNotificationData
{
    public function notificationType(): NotificationType
    {
        return NotificationType::ToolsUnpaidLevel2Notification;
    }
}
