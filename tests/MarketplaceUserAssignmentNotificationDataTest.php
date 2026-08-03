<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceUserAssignmentNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidNotificationDataException;

it('builds MarketplaceUserAssignmentNotificationData from its fields', function (): void {
    $data = new MarketplaceUserAssignmentNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        professional_count: 3,
        claim_token: 'tok_9f3ba71c',
    );

    expect($data->base_url)->toBe('https://example.test');
    expect($data->demand_id)->toBe(4321);
    expect($data->professional_count)->toBe(3);
    expect($data->claim_token)->toBe('tok_9f3ba71c');
});

it('allows claim_token to be null', function (): void {
    $data = new MarketplaceUserAssignmentNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        professional_count: 3,
        claim_token: null,
    );

    expect($data->claim_token)->toBeNull();
});

it('carries every field in the serialized payload', function (): void {
    $data = new MarketplaceUserAssignmentNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        professional_count: 3,
        claim_token: 'tok_9f3ba71c',
    );

    expect($data->toArray())
        ->toHaveKey('base_url', 'https://example.test')
        ->toHaveKey('demand_id', 4321)
        ->toHaveKey('professional_count', 3)
        ->toHaveKey('claim_token', 'tok_9f3ba71c');
});

it('round-trips MarketplaceUserAssignmentNotificationData via toArray and fromArray', function (): void {
    $original = new MarketplaceUserAssignmentNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        professional_count: 3,
        claim_token: 'tok_9f3ba71c',
    );

    $restored = MarketplaceUserAssignmentNotificationData::fromArray($original->toArray());

    expect($restored->base_url)->toBe($original->base_url);
    expect($restored->demand_id)->toBe($original->demand_id);
    expect($restored->professional_count)->toBe($original->professional_count);
    expect($restored->claim_token)->toBe($original->claim_token);
});

it('keeps demand_id and professional_count as integers across a real JSON round-trip', function (): void {
    $original = new MarketplaceUserAssignmentNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        professional_count: 3,
        claim_token: 'tok_9f3ba71c',
    );

    $decoded = json_decode(json_encode($original->toArray()), true);
    $restored = MarketplaceUserAssignmentNotificationData::fromArray($decoded);

    expect($restored->demand_id)->toBe(4321);
    expect($restored->professional_count)->toBe(3);
});

it('rejects a demand_id that arrives as a numeric string rather than coercing it', function (): void {
    expect(fn () => MarketplaceUserAssignmentNotificationData::fromArray([
        'base_url' => 'https://example.test',
        'demand_id' => '4321',
        'professional_count' => 3,
        'claim_token' => 'tok_9f3ba71c',
    ]))->toThrow(TypeError::class);
});

it('throws when professional_count is missing from the payload', function (): void {
    expect(fn () => MarketplaceUserAssignmentNotificationData::fromArray([
        'base_url' => 'https://example.test',
        'demand_id' => 4321,
    ]))->toThrow(InvalidNotificationDataException::class);
});

it('defaults claim_token to null when missing from the payload', function (): void {
    $restored = MarketplaceUserAssignmentNotificationData::fromArray([
        'base_url' => 'https://example.test',
        'demand_id' => 4321,
        'professional_count' => 3,
    ]);

    expect($restored->claim_token)->toBeNull();
});

it('reports the marketplace user-assignment notification type', function (): void {
    $data = new MarketplaceUserAssignmentNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        professional_count: 3,
        claim_token: 'tok_9f3ba71c',
    );

    expect($data->notificationType())->toBe(NotificationType::MarketplaceUserAssignmentNotification);
});
