<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceSatisfactionSurveyNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

it('builds MarketplaceSatisfactionSurveyNotificationData from its fields', function (): void {
    $data = new MarketplaceSatisfactionSurveyNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        satisfaction_token: 'tok_9f3ba71c',
    );

    expect($data->base_url)->toBe('https://example.test');
    expect($data->demand_id)->toBe(4321);
    expect($data->satisfaction_token)->toBe('tok_9f3ba71c');
});

it('carries every field in the serialized payload', function (): void {
    $data = new MarketplaceSatisfactionSurveyNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        satisfaction_token: 'tok_9f3ba71c',
    );

    expect($data->toArray())
        ->toHaveKey('base_url', 'https://example.test')
        ->toHaveKey('demand_id', 4321)
        ->toHaveKey('satisfaction_token', 'tok_9f3ba71c');
});

it('round-trips MarketplaceSatisfactionSurveyNotificationData via toArray and fromArray', function (): void {
    $original = new MarketplaceSatisfactionSurveyNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        satisfaction_token: 'tok_9f3ba71c',
    );

    $restored = MarketplaceSatisfactionSurveyNotificationData::fromArray($original->toArray());

    expect($restored->base_url)->toBe($original->base_url);
    expect($restored->demand_id)->toBe($original->demand_id);
    expect($restored->satisfaction_token)->toBe($original->satisfaction_token);
});

it('keeps demand_id an integer across a real JSON round-trip', function (): void {
    $original = new MarketplaceSatisfactionSurveyNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        satisfaction_token: 'tok_9f3ba71c',
    );

    $decoded = json_decode(json_encode($original->toArray()), true);
    $restored = MarketplaceSatisfactionSurveyNotificationData::fromArray($decoded);

    expect($restored->demand_id)->toBe(4321);
});

it('rejects a demand_id that arrives as a numeric string rather than coercing it', function (): void {
    expect(fn () => MarketplaceSatisfactionSurveyNotificationData::fromArray([
        'base_url' => 'https://example.test',
        'demand_id' => '4321',
        'satisfaction_token' => 'tok_9f3ba71c',
    ]))->toThrow(TypeError::class);
});

it('rejects a missing satisfaction_token', function (): void {
    expect(fn () => MarketplaceSatisfactionSurveyNotificationData::fromArray([
        'base_url' => 'https://example.test',
        'demand_id' => 4321,
    ]))->toThrow(Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidNotificationDataException::class);
});

it('reports the marketplace satisfaction-survey notification type', function (): void {
    $data = new MarketplaceSatisfactionSurveyNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        satisfaction_token: 'tok_9f3ba71c',
    );

    expect($data->notificationType())->toBe(NotificationType::MarketplaceSatisfactionSurveyNotification);
});
