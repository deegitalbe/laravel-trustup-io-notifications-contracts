<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Contracts;

use Deegitalbe\TrustupIoNotificationsContracts\Data\EmailContent;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\EmailTemplateLocaleGranularity;

interface EmailCapable
{
    public function toEmail(): EmailContent;

    public function emailTemplate(): string;

    public function emailTemplateLocaleGranularity(): EmailTemplateLocaleGranularity;
}
