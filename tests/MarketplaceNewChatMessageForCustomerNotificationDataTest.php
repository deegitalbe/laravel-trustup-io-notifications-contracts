<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceNewChatMessageForCustomerNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

it('builds MarketplaceNewChatMessageForCustomerNotificationData from its fields', function (): void {
    $data = new MarketplaceNewChatMessageForCustomerNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        claim_token: 'tok_9f3ba71c',
    );

    expect($data->base_url)->toBe('https://example.test');
    expect($data->demand_id)->toBe(4321);
    expect($data->claim_token)->toBe('tok_9f3ba71c');
});

it('allows claim_token to be null by default', function (): void {
    $data = new MarketplaceNewChatMessageForCustomerNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
    );

    expect($data->claim_token)->toBeNull();
});

it('carries every field in the serialized payload', function (): void {
    $data = new MarketplaceNewChatMessageForCustomerNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        claim_token: 'tok_9f3ba71c',
    );

    expect($data->toArray())
        ->toHaveKey('base_url', 'https://example.test')
        ->toHaveKey('demand_id', 4321)
        ->toHaveKey('claim_token', 'tok_9f3ba71c');
});

it('does not include legacy_conversation_id in the serialized payload', function (): void {
    $data = new MarketplaceNewChatMessageForCustomerNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        claim_token: 'tok_9f3ba71c',
    );

    expect($data->toArray())->not->toHaveKey('legacy_conversation_id');
});

it('round-trips MarketplaceNewChatMessageForCustomerNotificationData via toArray and fromArray', function (): void {
    $original = new MarketplaceNewChatMessageForCustomerNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        claim_token: 'tok_9f3ba71c',
    );

    $restored = MarketplaceNewChatMessageForCustomerNotificationData::fromArray($original->toArray());

    expect($restored->base_url)->toBe($original->base_url);
    expect($restored->demand_id)->toBe($original->demand_id);
    expect($restored->claim_token)->toBe($original->claim_token);
});

it('keeps demand_id an integer across a real JSON round-trip', function (): void {
    $original = new MarketplaceNewChatMessageForCustomerNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        claim_token: 'tok_9f3ba71c',
    );

    $decoded = json_decode(json_encode($original->toArray()), true);
    $restored = MarketplaceNewChatMessageForCustomerNotificationData::fromArray($decoded);

    expect($restored->demand_id)->toBe(4321);
});

it('rejects a demand_id that arrives as a numeric string rather than coercing it', function (): void {
    expect(fn () => MarketplaceNewChatMessageForCustomerNotificationData::fromArray([
        'base_url' => 'https://example.test',
        'demand_id' => '4321',
        'claim_token' => 'tok_9f3ba71c',
    ]))->toThrow(TypeError::class);
});

it('defaults claim_token to null when missing from the payload', function (): void {
    $restored = MarketplaceNewChatMessageForCustomerNotificationData::fromArray([
        'base_url' => 'https://example.test',
        'demand_id' => 4321,
    ]);

    expect($restored->claim_token)->toBeNull();
});

it('ignores a legacy legacy_conversation_id key still present in an incoming payload', function (): void {
    $restored = MarketplaceNewChatMessageForCustomerNotificationData::fromArray([
        'base_url' => 'https://example.test',
        'demand_id' => 4321,
        'claim_token' => 'tok_9f3ba71c',
        'legacy_conversation_id' => '98765',
    ]);

    expect($restored->base_url)->toBe('https://example.test');
    expect($restored->demand_id)->toBe(4321);
    expect($restored->claim_token)->toBe('tok_9f3ba71c');
    expect(property_exists($restored, 'legacy_conversation_id'))->toBeFalse();
});

it('reports the marketplace new-chat-message-for-customer notification type', function (): void {
    $data = new MarketplaceNewChatMessageForCustomerNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        claim_token: 'tok_9f3ba71c',
    );

    expect($data->notificationType())->toBe(NotificationType::MarketplaceNewChatMessageForCustomerNotification);
});
