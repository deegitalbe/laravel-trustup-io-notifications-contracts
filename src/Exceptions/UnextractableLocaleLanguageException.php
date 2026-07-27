<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Exceptions;

use RuntimeException;

class UnextractableLocaleLanguageException extends RuntimeException
{
    public function __construct(string $locale)
    {
        parent::__construct("Could not extract a language from locale [{$locale}].");
    }
}
