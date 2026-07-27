<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Enums\Source;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidRecipientException;
use Deegitalbe\TrustupIoNotificationsContracts\Request\Recipient;

// -------------------------------------------------------------------------
// AC-2: anonymous locale (normalisation, mandatory, serialization)
// -------------------------------------------------------------------------

it('anonymous with locale be-fr stores canonical fr-BE', function (): void {
    $recipient = Recipient::anonymous('user@example.com', null, [], locale: 'be-fr');

    expect($recipient->locale)->toBe('fr-BE');
});

it('anonymous toArray includes canonical locale', function (): void {
    $recipient = Recipient::anonymous('user@example.com', null, [], locale: 'fr-BE');

    expect($recipient->toArray())->toHaveKey('locale', 'fr-BE');
});

it('anonymous throws InvalidRecipientException when locale is null', function (): void {
    expect(fn () => Recipient::anonymous('user@example.com', null, [], locale: null))
        ->toThrow(InvalidRecipientException::class);
});

it('anonymous throws InvalidRecipientException when locale is unmappable zz', function (): void {
    expect(fn () => Recipient::anonymous('user@example.com', null, [], locale: 'zz'))
        ->toThrow(InvalidRecipientException::class);
});

it('identified locale is null', function (): void {
    $recipient = Recipient::identified('ext-1', Source::Tools);

    expect($recipient->locale)->toBeNull();
});

it('identified toArray omits locale', function (): void {
    $recipient = Recipient::identified('ext-1', Source::Tools);

    expect($recipient->toArray())->not->toHaveKey('locale');
});

it('fromArray round-trips anonymous payload with locale', function (): void {
    $original = Recipient::anonymous('user@example.com', null, [], locale: 'be-fr');
    $restored = Recipient::fromArray($original->toArray());

    expect($restored->locale)->toBe('fr-BE');
});

it('builds identified recipient from source and external user id', function (): void {
    $recipient = Recipient::identified('ext-1', Source::Tools);

    expect($recipient->source)->toBe(Source::Tools);
    expect($recipient->externalUserId)->toBe('ext-1');
    expect($recipient->isIdentified())->toBeTrue();
});

it('round-trips identified recipient via toArray and fromArray', function (): void {
    $original = Recipient::identified('ext-1', Source::Tools);
    $restored = Recipient::fromArray($original->toArray());

    expect($restored->source)->toBe($original->source);
    expect($restored->externalUserId)->toBe($original->externalUserId);
});

it('builds anonymous recipient with email only', function (): void {
    $recipient = Recipient::anonymous('user@example.com', null, [], locale: 'fr-BE');

    expect($recipient->email)->toBe('user@example.com');
    expect($recipient->isIdentified())->toBeFalse();
});

it('builds anonymous recipient with device tokens only', function (): void {
    $recipient = Recipient::anonymous(null, null, ['token-abc'], locale: 'nl-BE');

    expect($recipient->deviceTokens)->toBe(['token-abc']);
});

it('round-trips anonymous recipient with email via toArray and fromArray', function (): void {
    $original = Recipient::anonymous('user@example.com', null, [], locale: 'fr-BE');
    $restored = Recipient::fromArray($original->toArray());

    expect($restored->email)->toBe($original->email);
    expect($restored->phone)->toBe($original->phone);
    expect($restored->deviceTokens)->toBe($original->deviceTokens);
});

it('throws InvalidRecipientException when anonymous has no coordinates', function (): void {
    expect(fn () => Recipient::anonymous(null, null, []))
        ->toThrow(InvalidRecipientException::class);
});

it('throws InvalidRecipientException when fromArray has both identity and anonymous coordinates', function (): void {
    $mixedData = [
        'source' => Source::Tools->value,
        'external_user_id' => 'ext-1',
        'email' => 'user@example.com',
    ];

    expect(fn () => Recipient::fromArray($mixedData))
        ->toThrow(InvalidRecipientException::class);
});

// -------------------------------------------------------------------------
// AC1 (multi-source-routing): anonymous recipient carries source
// -------------------------------------------------------------------------

it('anonymous recipient with source assigned via withSource includes source in toArray', function (): void {
    $recipient = Recipient::anonymous('anon@example.com', null, [], locale: 'fr-BE')
        ->withSource(Source::Tools);

    expect($recipient->toArray())
        ->toHaveKey('source', Source::Tools->value);
});

it('anonymous recipient without source omits source key from toArray', function (): void {
    $recipient = Recipient::anonymous('anon@example.com', null, [], locale: 'fr-BE');

    expect($recipient->toArray())->not->toHaveKey('source');
});

it('anonymous recipient with source round-trips via toArray and fromArray', function (): void {
    $original = Recipient::anonymous('anon@example.com', null, [], locale: 'fr-BE')
        ->withSource(Source::Tools);

    $restored = Recipient::fromArray($original->toArray());

    expect($restored->source)->toBe(Source::Tools);
    expect($restored->email)->toBe('anon@example.com');
    expect($restored->isIdentified())->toBeFalse();
});

// -------------------------------------------------------------------------
// Identified recipient with a deferred (config-resolved) source
// -------------------------------------------------------------------------

it('builds an identified recipient with a null source when only the external id is given', function (): void {
    $recipient = Recipient::identified('ext-1');

    expect($recipient->source)->toBeNull();
    expect($recipient->externalUserId)->toBe('ext-1');
    expect($recipient->isIdentified())->toBeTrue();
});

it('omits the source key from toArray when an identified recipient has no source', function (): void {
    $recipient = Recipient::identified('ext-1');

    expect($recipient->toArray())->not->toHaveKey('source');
});

it('resolves the source later via withSource on an identified recipient', function (): void {
    $recipient = Recipient::identified('ext-1')->withSource(Source::Tools);

    expect($recipient->source)->toBe(Source::Tools);
    expect($recipient->isIdentified())->toBeTrue();
});

it('throws InvalidRecipientException when fromArray decodes an identified recipient without a source', function (): void {
    $data = ['identified' => true, 'external_user_id' => 'ext-1'];

    expect(fn () => Recipient::fromArray($data))
        ->toThrow(InvalidRecipientException::class);
});
