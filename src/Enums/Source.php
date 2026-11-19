<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Enums;

enum Source: string
{
    case Tools = 'tools';
    case Marketplace = 'marketplace';
    case T360 = 't360';
}
