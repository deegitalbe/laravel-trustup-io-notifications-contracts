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
        NotificationType::ToolsNewChatMessageForProfessionalNotification => [123, 4242],
        NotificationType::MarketplaceUserReassignNotification => [4321, 'https://marketplace.example/demands/4321'],
        NotificationType::MarketplaceAssignationActivationNotification => [4321, 'https://example.test/demands/4321'],
        NotificationType::MarketplaceNewChatMessageForCustomerNotification => [4321, '98765', 'claim-token'],
        NotificationType::ToolsProResponseReminderNotification => [4321, 987, 'Demand Title'],
        NotificationType::MarketplaceSatisfactionSurveyNotification => [4321, 'satisfaction-token'],
        NotificationType::ToolsNewDemandForProfessionalNotification => [4321, 4242, 'toiture', null, 'Titre', 'Description'],
        NotificationType::MarketplaceUserAssignmentNotification => [4321, 3],
        default => throw new LogicException("No minimal args defined for [{$type->value}] in coherence test."),
    };

    $instance = new $dataClass(...$minimalArgs);

    expect($instance->notificationType())->toBe($type);
})->with(NotificationType::cases());
