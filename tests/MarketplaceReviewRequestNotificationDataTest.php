<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceReviewRequestNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

it('builds MarketplaceReviewRequestNotificationData from its fields', function (): void {
    $data = new MarketplaceReviewRequestNotificationData(
        base_url: 'https://example.test',
        first_name: 'Marie',
        pro_name: 'Toiture Dubois',
        pro_slug: 'toiture-dubois',
    );

    expect($data->base_url)->toBe('https://example.test');
    expect($data->first_name)->toBe('Marie');
    expect($data->pro_name)->toBe('Toiture Dubois');
    expect($data->pro_slug)->toBe('toiture-dubois');
});

it('carries every field in the serialized payload', function (): void {
    $data = new MarketplaceReviewRequestNotificationData(
        base_url: 'https://example.test',
        first_name: 'Marie',
        pro_name: 'Toiture Dubois',
        pro_slug: 'toiture-dubois',
    );

    expect($data->toArray())
        ->toHaveKey('base_url', 'https://example.test')
        ->toHaveKey('first_name', 'Marie')
        ->toHaveKey('pro_name', 'Toiture Dubois')
        ->toHaveKey('pro_slug', 'toiture-dubois');
});

it('round-trips MarketplaceReviewRequestNotificationData via toArray and fromArray', function (): void {
    $original = new MarketplaceReviewRequestNotificationData(
        base_url: 'https://example.test',
        first_name: 'Marie',
        pro_name: 'Toiture Dubois',
        pro_slug: 'toiture-dubois',
    );

    $restored = MarketplaceReviewRequestNotificationData::fromArray($original->toArray());

    expect($restored->base_url)->toBe($original->base_url);
    expect($restored->first_name)->toBe($original->first_name);
    expect($restored->pro_name)->toBe($original->pro_name);
    expect($restored->pro_slug)->toBe($original->pro_slug);
});

it('reports the marketplace review-request notification type', function (): void {
    $data = new MarketplaceReviewRequestNotificationData(
        base_url: 'https://example.test',
        first_name: 'Marie',
        pro_name: 'Toiture Dubois',
        pro_slug: 'toiture-dubois',
    );

    expect($data->notificationType())->toBe(NotificationType::MarketplaceReviewRequestNotification);
});
