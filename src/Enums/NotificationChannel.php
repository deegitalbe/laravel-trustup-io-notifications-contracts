<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Enums;

enum NotificationChannel: string
{
    case Email = 'email';
    case Sms = 'sms';
    case Push = 'push';
}
