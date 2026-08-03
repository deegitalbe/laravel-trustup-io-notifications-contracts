<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceDemandTransmittedNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

it('builds MarketplaceDemandTransmittedNotificationData from its fields', function (): void {
    $data = new MarketplaceDemandTransmittedNotificationData(
        base_url: 'https://example.test',
        first_name: 'Marie',
        pro_name: 'Toiture Dubois',
        demand_id: 4321,
        claim_token: 'tok_9f3ba71c',
    );

    expect($data->base_url)->toBe('https://example.test');
    expect($data->first_name)->toBe('Marie');
    expect($data->pro_name)->toBe('Toiture Dubois');
    expect($data->demand_id)->toBe(4321);
    expect($data->claim_token)->toBe('tok_9f3ba71c');
});

it('carries every field in the serialized payload', function (): void {
    $data = new MarketplaceDemandTransmittedNotificationData(
        base_url: 'https://example.test',
        first_name: 'Marie',
        pro_name: 'Toiture Dubois',
        demand_id: 4321,
        claim_token: 'tok_9f3ba71c',
    );

    expect($data->toArray())
        ->toHaveKey('base_url', 'https://example.test')
        ->toHaveKey('first_name', 'Marie')
        ->toHaveKey('pro_name', 'Toiture Dubois')
        ->toHaveKey('demand_id', 4321)
        ->toHaveKey('claim_token', 'tok_9f3ba71c');
});

it('no longer carries pro_slug, the CTA now targets the demand itself', function (): void {
    $data = new MarketplaceDemandTransmittedNotificationData(
        base_url: 'https://example.test',
        first_name: 'Marie',
        pro_name: 'Toiture Dubois',
        demand_id: 4321,
        claim_token: 'tok_9f3ba71c',
    );

    expect($data->toArray())->not->toHaveKey('pro_slug');
});

it('round-trips MarketplaceDemandTransmittedNotificationData via toArray and fromArray', function (): void {
    $original = new MarketplaceDemandTransmittedNotificationData(
        base_url: 'https://example.test',
        first_name: 'Marie',
        pro_name: 'Toiture Dubois',
        demand_id: 4321,
        claim_token: 'tok_9f3ba71c',
    );

    $restored = MarketplaceDemandTransmittedNotificationData::fromArray($original->toArray());

    expect($restored->base_url)->toBe($original->base_url);
    expect($restored->first_name)->toBe($original->first_name);
    expect($restored->pro_name)->toBe($original->pro_name);
    expect($restored->demand_id)->toBe($original->demand_id);
    expect($restored->claim_token)->toBe($original->claim_token);
});

it('keeps demand_id an integer across a real JSON round-trip', function (): void {
    $original = new MarketplaceDemandTransmittedNotificationData(
        base_url: 'https://example.test',
        first_name: 'Marie',
        pro_name: 'Toiture Dubois',
        demand_id: 4321,
        claim_token: 'tok_9f3ba71c',
    );

    $decoded = json_decode(json_encode($original->toArray()), true);
    $restored = MarketplaceDemandTransmittedNotificationData::fromArray($decoded);

    expect($restored->demand_id)->toBe(4321);
});

it('rejects a demand_id that arrives as a numeric string rather than coercing it', function (): void {
    expect(fn () => MarketplaceDemandTransmittedNotificationData::fromArray([
        'base_url' => 'https://example.test',
        'first_name' => 'Marie',
        'pro_name' => 'Toiture Dubois',
        'demand_id' => '4321',
        'claim_token' => 'tok_9f3ba71c',
    ]))->toThrow(TypeError::class);
});

it('reports the marketplace demand-transmitted notification type', function (): void {
    $data = new MarketplaceDemandTransmittedNotificationData(
        base_url: 'https://example.test',
        first_name: 'Marie',
        pro_name: 'Toiture Dubois',
        demand_id: 4321,
        claim_token: 'tok_9f3ba71c',
    );

    expect($data->notificationType())->toBe(NotificationType::MarketplaceDemandTransmittedNotification);
});
