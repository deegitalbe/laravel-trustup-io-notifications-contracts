<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceAssignationActivationNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

it('builds MarketplaceAssignationActivationNotificationData from its fields', function (): void {
    $data = new MarketplaceAssignationActivationNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        action_url: 'https://example.test/demands/4321',
    );

    expect($data->base_url)->toBe('https://example.test');
    expect($data->demand_id)->toBe(4321);
    expect($data->action_url)->toBe('https://example.test/demands/4321');
});

it('carries every field in the serialized payload', function (): void {
    $data = new MarketplaceAssignationActivationNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        action_url: 'https://example.test/demands/4321',
    );

    expect($data->toArray())
        ->toHaveKey('base_url', 'https://example.test')
        ->toHaveKey('demand_id', 4321)
        ->toHaveKey('action_url', 'https://example.test/demands/4321');
});

it('round-trips MarketplaceAssignationActivationNotificationData via toArray and fromArray', function (): void {
    $original = new MarketplaceAssignationActivationNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        action_url: 'https://example.test/demands/4321',
    );

    $restored = MarketplaceAssignationActivationNotificationData::fromArray($original->toArray());

    expect($restored->base_url)->toBe($original->base_url);
    expect($restored->demand_id)->toBe($original->demand_id);
    expect($restored->action_url)->toBe($original->action_url);
});

it('keeps demand_id an integer across a real JSON round-trip', function (): void {
    $original = new MarketplaceAssignationActivationNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        action_url: 'https://example.test/demands/4321',
    );

    $decoded = json_decode(json_encode($original->toArray()), true);
    $restored = MarketplaceAssignationActivationNotificationData::fromArray($decoded);

    expect($restored->demand_id)->toBe(4321);
});

it('rejects a demand_id that arrives as a numeric string rather than coercing it', function (): void {
    expect(fn () => MarketplaceAssignationActivationNotificationData::fromArray([
        'base_url' => 'https://example.test',
        'demand_id' => '4321',
        'action_url' => 'https://example.test/demands/4321',
    ]))->toThrow(TypeError::class);
});

it('throws when action_url is missing from the fromArray payload', function (): void {
    expect(fn () => MarketplaceAssignationActivationNotificationData::fromArray([
        'base_url' => 'https://example.test',
        'demand_id' => 4321,
    ]))->toThrow(Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidNotificationDataException::class);
});

it('reports the marketplace assignation-activation notification type', function (): void {
    $data = new MarketplaceAssignationActivationNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        action_url: 'https://example.test/demands/4321',
    );

    expect($data->notificationType())->toBe(NotificationType::MarketplaceAssignationActivationNotification);
});
