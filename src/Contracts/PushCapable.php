<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Contracts;

use Deegitalbe\TrustupIoNotificationsContracts\Data\PushContent;

interface PushCapable
{
    public function toPush(): PushContent;
}
