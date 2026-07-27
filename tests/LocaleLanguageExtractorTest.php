<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\UnextractableLocaleLanguageException;
use Deegitalbe\TrustupIoNotificationsContracts\Support\LocaleLanguageExtractor;

it('extracts the primary language from a full locale', function (): void {
    expect(LocaleLanguageExtractor::language('en-BE'))->toBe('en')
        ->and(LocaleLanguageExtractor::language('fr-FR'))->toBe('fr');
});

it('returns the language when the locale is already a bare language code', function (): void {
    expect(LocaleLanguageExtractor::language('nl'))->toBe('nl');
});

it('defaults to the ICU fallback language for an empty locale', function (): void {
    expect(LocaleLanguageExtractor::language(''))->toBe('en');
});

it('throws when no language subtag can be extracted', function (): void {
    expect(fn () => LocaleLanguageExtractor::language('und'))
        ->toThrow(UnextractableLocaleLanguageException::class);
});
