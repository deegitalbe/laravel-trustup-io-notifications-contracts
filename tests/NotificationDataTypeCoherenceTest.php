<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

it('every NotificationType data class returns its own notificationType without exception', function (NotificationType $type): void {
    $dataClass = $type->dataClass();
    $minimalArgs = match ($type) {
        NotificationType::ToolsTestNotification => ['Test Title', 'Test Body'],
        NotificationType::ToolsFullLocaleTestNotification => ['Test Title', 'Test Body'],
        NotificationType::MarketplaceReviewRequestNotification => ['First Name', 'Pro Name', 'pro-slug'],
        NotificationType::ToolsNewDemandNotification => ['First Name', 'Workfield Name', 4242],
        NotificationType::MarketplaceDemandTransmittedNotification => ['First Name', 'Pro Name', 4321, 'claim-token'],
        NotificationType::MarketplaceDemandReceivedNotification => [4321],
        NotificationType::MarketplaceNewChatMessageForCustomerNotification => [4321, '98765', 'claim-token'],
        default => throw new LogicException("No minimal args defined for [{$type->value}] in coherence test."),
    };

    $instance = new $dataClass(...$minimalArgs);

    expect($instance->notificationType())->toBe($type);
})->with(NotificationType::cases());
