<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceDemandReceivedNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

it('builds MarketplaceDemandReceivedNotificationData from its fields', function (): void {
    $data = new MarketplaceDemandReceivedNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        ai_session_id: 'sess_9f3ba71c',
        claim_token: 'tok_9f3ba71c',
        first_name: 'Jean',
    );

    expect($data->base_url)->toBe('https://example.test');
    expect($data->demand_id)->toBe(4321);
    expect($data->ai_session_id)->toBe('sess_9f3ba71c');
    expect($data->claim_token)->toBe('tok_9f3ba71c');
    expect($data->first_name)->toBe('Jean');
});

it('allows ai_session_id, claim_token and first_name to be null', function (): void {
    $data = new MarketplaceDemandReceivedNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        ai_session_id: null,
        claim_token: null,
        first_name: null,
    );

    expect($data->ai_session_id)->toBeNull();
    expect($data->claim_token)->toBeNull();
    expect($data->first_name)->toBeNull();
});

it('carries every field in the serialized payload', function (): void {
    $data = new MarketplaceDemandReceivedNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        ai_session_id: 'sess_9f3ba71c',
        claim_token: 'tok_9f3ba71c',
        first_name: 'Jean',
    );

    expect($data->toArray())
        ->toHaveKey('base_url', 'https://example.test')
        ->toHaveKey('demand_id', 4321)
        ->toHaveKey('ai_session_id', 'sess_9f3ba71c')
        ->toHaveKey('claim_token', 'tok_9f3ba71c')
        ->toHaveKey('first_name', 'Jean');
});

it('round-trips MarketplaceDemandReceivedNotificationData via toArray and fromArray', function (): void {
    $original = new MarketplaceDemandReceivedNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        ai_session_id: 'sess_9f3ba71c',
        claim_token: 'tok_9f3ba71c',
        first_name: 'Jean',
    );

    $restored = MarketplaceDemandReceivedNotificationData::fromArray($original->toArray());

    expect($restored->base_url)->toBe($original->base_url);
    expect($restored->demand_id)->toBe($original->demand_id);
    expect($restored->ai_session_id)->toBe($original->ai_session_id);
    expect($restored->claim_token)->toBe($original->claim_token);
    expect($restored->first_name)->toBe($original->first_name);
});

it('keeps demand_id an integer across a real JSON round-trip', function (): void {
    $original = new MarketplaceDemandReceivedNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        ai_session_id: 'sess_9f3ba71c',
        claim_token: 'tok_9f3ba71c',
        first_name: 'Jean',
    );

    $decoded = json_decode(json_encode($original->toArray()), true);
    $restored = MarketplaceDemandReceivedNotificationData::fromArray($decoded);

    expect($restored->demand_id)->toBe(4321);
});

it('rejects a demand_id that arrives as a numeric string rather than coercing it', function (): void {
    expect(fn () => MarketplaceDemandReceivedNotificationData::fromArray([
        'base_url' => 'https://example.test',
        'demand_id' => '4321',
        'ai_session_id' => 'sess_9f3ba71c',
        'claim_token' => 'tok_9f3ba71c',
        'first_name' => 'Jean',
    ]))->toThrow(TypeError::class);
});

it('defaults ai_session_id, claim_token and first_name to null when missing from the payload', function (): void {
    $restored = MarketplaceDemandReceivedNotificationData::fromArray([
        'base_url' => 'https://example.test',
        'demand_id' => 4321,
    ]);

    expect($restored->ai_session_id)->toBeNull();
    expect($restored->claim_token)->toBeNull();
    expect($restored->first_name)->toBeNull();
});

it('reports the marketplace demand-received notification type', function (): void {
    $data = new MarketplaceDemandReceivedNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        ai_session_id: 'sess_9f3ba71c',
        claim_token: 'tok_9f3ba71c',
        first_name: 'Jean',
    );

    expect($data->notificationType())->toBe(NotificationType::MarketplaceDemandReceivedNotification);
});
