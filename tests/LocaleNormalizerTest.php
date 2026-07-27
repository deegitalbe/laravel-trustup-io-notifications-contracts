<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Support\LocaleNormalizer;

it('maps proprietary be-fr to fr-BE', function (): void {
    $normalizer = new LocaleNormalizer;

    expect($normalizer->normalize('be-fr'))->toBe('fr-BE');
});

it('maps proprietary be-nl to nl-BE', function (): void {
    $normalizer = new LocaleNormalizer;

    expect($normalizer->normalize('be-nl'))->toBe('nl-BE');
});

it('maps proprietary be-en to en-BE', function (): void {
    $normalizer = new LocaleNormalizer;

    expect($normalizer->normalize('be-en'))->toBe('en-BE');
});

it('maps proprietary be-de to de-BE', function (): void {
    $normalizer = new LocaleNormalizer;

    expect($normalizer->normalize('be-de'))->toBe('de-BE');
});

it('maps proprietary fr-fr to fr-FR', function (): void {
    $normalizer = new LocaleNormalizer;

    expect($normalizer->normalize('fr-fr'))->toBe('fr-FR');
});

it('maps proprietary nl-nl to nl-NL', function (): void {
    $normalizer = new LocaleNormalizer;

    expect($normalizer->normalize('nl-nl'))->toBe('nl-NL');
});

it('maps proprietary fr-en to en-FR', function (): void {
    $normalizer = new LocaleNormalizer;

    expect($normalizer->normalize('fr-en'))->toBe('en-FR');
});

it('maps proprietary en-nl to en-NL', function (): void {
    $normalizer = new LocaleNormalizer;

    expect($normalizer->normalize('en-nl'))->toBe('en-NL');
});

it('maps bare language fr to fr-BE', function (): void {
    $normalizer = new LocaleNormalizer;

    expect($normalizer->normalize('fr'))->toBe('fr-BE');
});

it('maps bare language nl to nl-BE', function (): void {
    $normalizer = new LocaleNormalizer;

    expect($normalizer->normalize('nl'))->toBe('nl-BE');
});

it('maps bare language en to en-BE', function (): void {
    $normalizer = new LocaleNormalizer;

    expect($normalizer->normalize('en'))->toBe('en-BE');
});

it('maps bare language de to de-BE', function (): void {
    $normalizer = new LocaleNormalizer;

    expect($normalizer->normalize('de'))->toBe('de-BE');
});

it('passes through already-BCP47 fr-BE unchanged', function (): void {
    $normalizer = new LocaleNormalizer;

    expect($normalizer->normalize('fr-BE'))->toBe('fr-BE');
});

it('passes through already-BCP47 en-FR unchanged', function (): void {
    $normalizer = new LocaleNormalizer;

    expect($normalizer->normalize('en-FR'))->toBe('en-FR');
});

it('normalises mixed separator fr_be (underscore) to fr-BE', function (): void {
    $normalizer = new LocaleNormalizer;

    expect($normalizer->normalize('fr_be'))->toBe('fr-BE');
});

it('normalises uppercase FR-BE to fr-BE', function (): void {
    $normalizer = new LocaleNormalizer;

    expect($normalizer->normalize('FR-BE'))->toBe('fr-BE');
});

it('normalises lowercase fr-be to fr-BE', function (): void {
    $normalizer = new LocaleNormalizer;

    expect($normalizer->normalize('fr-be'))->toBe('fr-BE');
});

it('returns null for empty string', function (): void {
    $normalizer = new LocaleNormalizer;

    expect($normalizer->normalize(''))->toBeNull();
});

it('returns null for null input', function (): void {
    $normalizer = new LocaleNormalizer;

    expect($normalizer->normalize(null))->toBeNull();
});

it('returns null for unknown zz-zz', function (): void {
    $normalizer = new LocaleNormalizer;

    expect($normalizer->normalize('zz-zz'))->toBeNull();
});
