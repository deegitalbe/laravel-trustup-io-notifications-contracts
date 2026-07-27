<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Contracts;

use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

interface NotificationData extends Serializable
{
    public function notificationType(): NotificationType;
}
