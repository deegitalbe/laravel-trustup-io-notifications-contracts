<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidNotificationDataException;
use Deegitalbe\TrustupIoNotificationsContracts\Tests\Stubs\NestedSerializableStub;
use Deegitalbe\TrustupIoNotificationsContracts\Tests\Stubs\NestingSerializableStub;
use Deegitalbe\TrustupIoNotificationsContracts\Tests\Stubs\NullableNoDefaultStub;
use Deegitalbe\TrustupIoNotificationsContracts\Tests\Stubs\ScalarSerializableStub;

it('serializes every public constructor property by name', function (): void {
    $stub = new ScalarSerializableStub('hello', 3, ['a' => 1], 'x');

    expect($stub->toArray())->toBe([
        'title' => 'hello',
        'count' => 3,
        'meta' => ['a' => 1],
        'note' => 'x',
    ]);
});

it('recursively serializes nested Serializable properties', function (): void {
    $stub = new NestingSerializableStub('parent', new NestedSerializableStub('deep'));

    expect($stub->toArray())->toBe([
        'label' => 'parent',
        'child' => ['value' => 'deep'],
    ]);
});

it('round-trips through toArray and fromArray', function (): void {
    $original = new ScalarSerializableStub('hi', 7, ['k' => 'v'], 'note');
    $restored = ScalarSerializableStub::fromArray($original->toArray());

    expect($restored->title)->toBe('hi')
        ->and($restored->count)->toBe(7)
        ->and($restored->meta)->toBe(['k' => 'v'])
        ->and($restored->note)->toBe('note');
});

it('falls back to constructor defaults when a key is absent in fromArray', function (): void {
    $restored = ScalarSerializableStub::fromArray(['title' => 'only', 'count' => 1]);

    expect($restored->meta)->toBe([])
        ->and($restored->note)->toBeNull();
});

it('passes null for a nullable parameter with no default when its key is absent', function (): void {
    expect(NullableNoDefaultStub::fromArray([])->value)->toBeNull();
});

it('throws when a required non-nullable parameter is absent from the payload', function (): void {
    expect(fn () => NestedSerializableStub::fromArray([]))
        ->toThrow(InvalidNotificationDataException::class);
});
