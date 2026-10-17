<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceProfileReviewReminderNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidNotificationDataException;

function profileReviewReminderData(): MarketplaceProfileReviewReminderNotificationData
{
    return new MarketplaceProfileReviewReminderNotificationData(
        base_url: 'https://example.test',
        first_name: 'Marie',
        professional_id: 'pro-1',
        professional_name: 'Toiture Dubois',
    );
}

it('carries every field in the serialized payload', function (): void {
    expect(profileReviewReminderData()->toArray())->toBe([
        'base_url' => 'https://example.test',
        'first_name' => 'Marie',
        'professional_id' => 'pro-1',
        'professional_name' => 'Toiture Dubois',
    ]);
});

it('round-trips via toArray and fromArray', function (): void {
    $original = profileReviewReminderData();

    expect(MarketplaceProfileReviewReminderNotificationData::fromArray($original->toArray())->toArray())->toBe($original->toArray());
});

it('hands every field to the email template', function (): void {
    expect(profileReviewReminderData()->toEmail()->variables)->toBe(profileReviewReminderData()->toArray());
});

it('reports the marketplace profile-review-reminder notification type', function (): void {
    expect(profileReviewReminderData()->notificationType())->toBe(NotificationType::MarketplaceProfileReviewReminderNotification);
});

it('fails loudly when the professional id is missing', function (): void {
    expect(fn () => MarketplaceProfileReviewReminderNotificationData::fromArray([
        'base_url' => 'https://example.test',
        'first_name' => 'Marie',
        'professional_name' => 'Toiture Dubois',
    ]))->toThrow(InvalidNotificationDataException::class);
});
