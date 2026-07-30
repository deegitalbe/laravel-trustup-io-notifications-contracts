<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationChannel;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\Source;

it('source() returns the Source derived from the value prefix for every case', function (NotificationType $type): void {
    expect(fn () => $type->source())->not->toThrow(Throwable::class);
    $prefix = explode('.', $type->value)[0];
    expect($type->source()->value)->toBe($prefix);
})->with(NotificationType::cases());

it('supportedChannels() returns a non-empty array of NotificationChannel instances for every case', function (NotificationType $type): void {
    $channels = $type->supportedChannels();
    expect($channels)->toBeArray()->not->toBeEmpty();
    foreach ($channels as $channel) {
        expect($channel)->toBeInstanceOf(NotificationChannel::class);
    }
})->with(NotificationType::cases());

it('supportedChannels() for ToolsTestNotification returns email, sms and push', function (): void {
    $channels = NotificationType::ToolsTestNotification->supportedChannels();
    expect($channels)
        ->toContain(NotificationChannel::Email)
        ->toContain(NotificationChannel::Sms)
        ->toContain(NotificationChannel::Push);
});

it('forSource(Tools) returns only tools.* types', function (): void {
    $types = NotificationType::forSource(Source::Tools);
    expect($types)->not->toBeEmpty();
    foreach ($types as $type) {
        expect($type->value)->toStartWith('tools.');
    }
});

it('forSource(Marketplace) returns only marketplace.* types', function (): void {
    $types = NotificationType::forSource(Source::Marketplace);
    expect($types)->not->toBeEmpty();
    foreach ($types as $type) {
        expect($type->value)->toStartWith('marketplace.');
    }
});

it('supportedChannels() for MarketplaceReviewRequestNotification returns only email', function (): void {
    $channels = NotificationType::MarketplaceReviewRequestNotification->supportedChannels();

    expect($channels)->toBe([NotificationChannel::Email]);
});

it('supportedChannels() for ToolsNewDemandNotification returns only email', function (): void {
    $channels = NotificationType::ToolsNewDemandNotification->supportedChannels();

    expect($channels)->toBe([NotificationChannel::Email]);
});

it('supportedChannels() for MarketplaceDemandTransmittedNotification returns only email', function (): void {
    $channels = NotificationType::MarketplaceDemandTransmittedNotification->supportedChannels();

    expect($channels)->toBe([NotificationChannel::Email]);
});

it('supportedChannels() for MarketplaceNewChatMessageForCustomerNotification returns only email', function (): void {
    $channels = NotificationType::MarketplaceNewChatMessageForCustomerNotification->supportedChannels();

    expect($channels)->toBe([NotificationChannel::Email]);
});
