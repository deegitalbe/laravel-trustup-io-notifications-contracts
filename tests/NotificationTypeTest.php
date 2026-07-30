<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceDemandReceivedNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceNewChatMessageForCustomerNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceReviewRequestNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsTestNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\Source;

it('NotificationType::ToolsTestNotification returns FQCN of ToolsTestNotificationData', function (): void {
    expect(NotificationType::ToolsTestNotification->dataClass())
        ->toBe(ToolsTestNotificationData::class);
});

it('every NotificationType case has a dataClass without exception', function (NotificationType $type): void {
    expect($type->dataClass())->toBeString()->not->toBeEmpty();
})->with(NotificationType::cases());

it('NotificationType::MarketplaceReviewRequestNotification maps to Marketplace source, its slug and data class', function (): void {
    $type = NotificationType::MarketplaceReviewRequestNotification;

    expect($type->source())->toBe(Source::Marketplace);
    expect($type->slug())->toBe('marketplace-review-request-notification');
    expect($type->dataClass())->toBe(MarketplaceReviewRequestNotificationData::class);
});

it('NotificationType::MarketplaceDemandReceivedNotification maps to Marketplace source, its slug and data class', function (): void {
    $type = NotificationType::MarketplaceDemandReceivedNotification;

    expect($type->source())->toBe(Source::Marketplace);
    expect($type->slug())->toBe('marketplace-demand-received-notification');
    expect($type->dataClass())->toBe(MarketplaceDemandReceivedNotificationData::class);
});

it('NotificationType::MarketplaceNewChatMessageForCustomerNotification maps to Marketplace source, its slug and data class', function (): void {
    $type = NotificationType::MarketplaceNewChatMessageForCustomerNotification;

    expect($type->source())->toBe(Source::Marketplace);
    expect($type->slug())->toBe('marketplace-new-chat-message-for-customer-notification');
    expect($type->dataClass())->toBe(MarketplaceNewChatMessageForCustomerNotificationData::class);
});
