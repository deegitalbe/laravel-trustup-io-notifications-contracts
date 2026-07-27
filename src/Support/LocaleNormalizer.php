<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Support;

use Locale;

class LocaleNormalizer
{
    private const string DEFAULT_REGION = 'BE';

    /** @var list<string> */
    private const array KNOWN_LANGUAGES = ['fr', 'nl', 'en', 'de'];

    /** @var array<string, string> */
    private const array PROPRIETARY_MAP = [
        'be-fr' => 'fr-BE',
        'be-nl' => 'nl-BE',
        'be-en' => 'en-BE',
        'be-de' => 'de-BE',
        'fr-fr' => 'fr-FR',
        'nl-nl' => 'nl-NL',
        'fr-en' => 'en-FR',
        'en-nl' => 'en-NL',
    ];

    public static function normalize(?string $raw): ?string
    {
        if ($raw === null || trim($raw) === '') {
            return null;
        }

        $normalized = strtolower(str_replace('_', '-', $raw));

        if (isset(self::PROPRIETARY_MAP[$normalized])) {
            return self::PROPRIETARY_MAP[$normalized];
        }

        $lang = Locale::getPrimaryLanguage($raw);

        if ($lang === null) { // @codeCoverageIgnore
            return null; // @codeCoverageIgnore
        } // @codeCoverageIgnore

        if (! in_array($lang, self::KNOWN_LANGUAGES, strict: true)) {
            return null;
        }

        $region = Locale::getRegion($raw);

        if ($region !== null && $region !== '') {
            return "{$lang}-{$region}";
        }

        return "{$lang}-".self::DEFAULT_REGION;
    }
}
