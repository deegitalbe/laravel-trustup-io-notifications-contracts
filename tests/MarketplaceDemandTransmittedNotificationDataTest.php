<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceDemandTransmittedNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

it('builds MarketplaceDemandTransmittedNotificationData from its fields', function (): void {
    $data = new MarketplaceDemandTransmittedNotificationData(
        first_name: 'Marie',
        pro_name: 'Toiture Dubois',
        pro_slug: 'toiture-dubois',
    );

    expect($data->first_name)->toBe('Marie');
    expect($data->pro_name)->toBe('Toiture Dubois');
    expect($data->pro_slug)->toBe('toiture-dubois');
});

it('carries every field in the serialized payload', function (): void {
    $data = new MarketplaceDemandTransmittedNotificationData(
        first_name: 'Marie',
        pro_name: 'Toiture Dubois',
        pro_slug: 'toiture-dubois',
    );

    expect($data->toArray())
        ->toHaveKey('first_name', 'Marie')
        ->toHaveKey('pro_name', 'Toiture Dubois')
        ->toHaveKey('pro_slug', 'toiture-dubois');
});

it('round-trips MarketplaceDemandTransmittedNotificationData via toArray and fromArray', function (): void {
    $original = new MarketplaceDemandTransmittedNotificationData(
        first_name: 'Marie',
        pro_name: 'Toiture Dubois',
        pro_slug: 'toiture-dubois',
    );

    $restored = MarketplaceDemandTransmittedNotificationData::fromArray($original->toArray());

    expect($restored->first_name)->toBe($original->first_name);
    expect($restored->pro_name)->toBe($original->pro_name);
    expect($restored->pro_slug)->toBe($original->pro_slug);
});

it('reports the marketplace demand-transmitted notification type', function (): void {
    $data = new MarketplaceDemandTransmittedNotificationData(
        first_name: 'Marie',
        pro_name: 'Toiture Dubois',
        pro_slug: 'toiture-dubois',
    );

    expect($data->notificationType())->toBe(NotificationType::MarketplaceDemandTransmittedNotification);
});
