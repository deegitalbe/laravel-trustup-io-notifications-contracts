<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Support;

use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\UnextractableLocaleLanguageException;
use Locale;

class LocaleLanguageExtractor
{
    public static function language(string $locale): string
    {
        $language = Locale::getPrimaryLanguage($locale);

        if ($language === null || $language === '') { // @phpstan-ignore-line
            throw new UnextractableLocaleLanguageException($locale);
        }

        return $language;
    }
}
