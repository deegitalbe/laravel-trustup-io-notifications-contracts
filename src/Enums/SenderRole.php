<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Enums;

/**
 * Who sends an email. The contract only names the role; each consuming app
 * maps it to a verified address in its own configuration.
 */
enum SenderRole: string
{
    case Default = 'default';
    case CreditControl = 'credit_control';
}
