<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Contracts;

use Deegitalbe\TrustupIoNotificationsContracts\Data\SmsContent;

interface SmsCapable
{
    public function toSms(): SmsContent;
}
