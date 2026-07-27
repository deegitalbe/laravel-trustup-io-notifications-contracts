<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Enums;

enum EmailTemplateLocaleGranularity: string
{
    case Language = 'language';
    case Locale = 'locale';
}
