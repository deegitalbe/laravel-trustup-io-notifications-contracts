<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

it('every NotificationType data class returns its own notificationType without exception', function (NotificationType $type): void {
    $dataClass = $type->dataClass();
    $minimalArgs = match ($type) {
        NotificationType::ToolsTestNotification => ['https://example.test', 'Test Title', 'Test Body'],
        NotificationType::ToolsFullLocaleTestNotification => ['https://example.test', 'Test Title', 'Test Body'],
        NotificationType::MarketplaceReviewRequestNotification => ['https://example.test', 'First Name', 'Pro Name', 'pro-slug'],
        NotificationType::ToolsNewDemandNotification => ['https://example.test', 'First Name', 'Workfield Name', 4242],
        NotificationType::MarketplaceDemandTransmittedNotification => ['https://example.test', 'First Name', 'Pro Name', 4321, 'claim-token'],
        NotificationType::MarketplaceDemandReceivedNotification => ['https://example.test', 4321],
        NotificationType::ToolsNewChatMessageForProfessionalNotification => ['https://example.test', 123, 4242],
        NotificationType::MarketplaceUserReassignNotification => ['https://example.test', 4321, 'claim-token'],
        NotificationType::MarketplaceAssignationActivationNotification => ['https://example.test', 4321, 'claim-token'],
        NotificationType::MarketplaceNewChatMessageForCustomerNotification => ['https://example.test', 4321, 'claim-token'],
        NotificationType::ToolsProResponseReminderNotification => ['https://example.test', 4321, 987, 'Demand Title'],
        NotificationType::MarketplaceSatisfactionSurveyNotification => ['https://example.test', 4321, 'satisfaction-token'],
        NotificationType::ToolsNewDemandForProfessionalNotification => ['https://example.test', 4321, 4242, 'toiture', null, 'Titre', 'Description'],
        NotificationType::MarketplaceUserAssignmentNotification => ['https://example.test', 4321, 3],
        NotificationType::MarketplaceUnclaimedDemandReminderNotification => ['https://example.test', 4321, 'Renovation de salle de bain', 'Plomberie'],
        default => throw new LogicException("No minimal args defined for [{$type->value}] in coherence test."),
    };

    $instance = new $dataClass(...$minimalArgs);

    expect($instance->notificationType())->toBe($type);
})->with(NotificationType::cases());
