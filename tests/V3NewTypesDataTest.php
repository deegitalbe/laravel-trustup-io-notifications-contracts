<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceAssignedProsReminderNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceNewInterestedProsNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplacePlatformFeedbackNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplacePlatformFeedbackReminderNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidNotificationDataException;

$pros = [['name' => 'Dupont SRL', 'logo' => 'https://example.test/logo.png', 'trust_score' => '9.4', 'trust_score_label' => 'excellent', 'call_url' => 'tel:+32470000000']];
$rating = [['note' => 1, 'url' => 'https://example.test/feedback?note=1'], ['note' => 5, 'url' => 'https://example.test/feedback?note=5']];

dataset('v3 new type classes', [
    'new interested pros' => [MarketplaceNewInterestedProsNotificationData::class, NotificationType::MarketplaceNewInterestedProsNotification, ['claim_token' => 'tok', 'pros' => $pros, 'demand_description' => 'D', 'demand_cancel_url' => 'https://example.test/cancel', 'demand_illustration_url' => 'https://example.test/p.jpg', 'professional_count' => 3, 'workfield_label' => 'Plombier', 'city' => 'Liège', 'single_professional' => true]],
    'assigned pros reminder' => [MarketplaceAssignedProsReminderNotificationData::class, NotificationType::MarketplaceAssignedProsReminderNotification, ['claim_token' => 'tok', 'pros' => $pros, 'demand_description' => 'D', 'demand_cancel_url' => 'https://example.test/cancel', 'demand_illustration_url' => 'https://example.test/p.jpg', 'professional_count' => 3, 'workfield_label' => 'Plombier', 'city' => 'Liège', 'single_professional' => true]],
    'platform feedback' => [MarketplacePlatformFeedbackNotificationData::class, NotificationType::MarketplacePlatformFeedbackNotification, ['first_name' => 'Camille', 'rating_links' => $rating]],
    'platform feedback reminder' => [MarketplacePlatformFeedbackReminderNotificationData::class, NotificationType::MarketplacePlatformFeedbackReminderNotification, ['first_name' => 'Camille', 'pro_name' => 'Dupont SRL', 'rating_links' => $rating]],
]);

it('builds from the required keys only and defaults every optional key to null', function (string $class, NotificationType $type, array $optional): void {
    $data = $class::fromArray(['base_url' => 'https://example.test', 'demand_id' => 4321]);

    expect($data->base_url)->toBe('https://example.test');
    expect($data->demand_id)->toBe(4321);

    foreach (array_keys($optional) as $key) {
        expect($data->{$key})->toBeNull();
    }
})->with('v3 new type classes');

it('reads every optional key when present and survives a JSON round trip', function (string $class, NotificationType $type, array $optional): void {
    $original = $class::fromArray(['base_url' => 'https://example.test', 'demand_id' => 4321] + $optional);
    $restored = $class::fromArray(json_decode(json_encode($original->toArray()), true));

    foreach ($optional as $key => $value) {
        expect($original->{$key})->toBe($value);
    }
    expect($restored->toArray())->toBe($original->toArray());
})->with('v3 new type classes');

it('reports its notification type', function (string $class, NotificationType $type): void {
    expect($class::fromArray(['base_url' => 'https://example.test', 'demand_id' => 1])->notificationType())->toBe($type);
})->with('v3 new type classes');

it('fails loudly when a required key is missing', function (string $class): void {
    expect(fn () => $class::fromArray(['base_url' => 'https://example.test']))
        ->toThrow(InvalidNotificationDataException::class);
})->with([
    MarketplaceNewInterestedProsNotificationData::class,
    MarketplaceAssignedProsReminderNotificationData::class,
    MarketplacePlatformFeedbackNotificationData::class,
    MarketplacePlatformFeedbackReminderNotificationData::class,
]);
