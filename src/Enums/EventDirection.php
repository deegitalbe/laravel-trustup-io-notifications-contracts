<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Enums;

enum EventDirection: string
{
    case Request = 'request';
    case Status = 'status';
    case Engagement = 'engagement';
}
