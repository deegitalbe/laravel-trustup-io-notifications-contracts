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
        NotificationType::MarketplaceNewChatMessageForCustomerNotification => ['https://example.test', 4321, 'fr', 'claim-token', 'cg-guid'],
        NotificationType::ToolsProResponseReminderNotification => ['https://example.test', 4321, 987, 'Demand Title'],
        NotificationType::ToolsProSecondReminderNotification => ['https://example.test', 4321, 987, 'Demand Title'],
        NotificationType::MarketplaceSatisfactionSurveyNotification => ['https://example.test', 4321, 'satisfaction-token'],
        NotificationType::ToolsNewDemandForProfessionalNotification => ['https://example.test', 4321, 4242, 'toiture', null, 'Titre', 'Description'],
        NotificationType::ToolsNewDemandForProfessionalFreemiumNotification => ['https://example.test', 4321, 4242, 'toiture', null, 'Titre', 'Description', 987, 'claim-token'],
        NotificationType::MarketplaceUserAssignmentNotification => ['https://example.test', 4321, 3],
        NotificationType::MarketplaceUnclaimedDemandReminderNotification => ['https://example.test', 4321, 'Renovation de salle de bain', 'Plomberie'],
        NotificationType::WhitelabelDemandReceivedNotification => ['https://example.test', 4321],
        NotificationType::WhitelabelNewChatMessageNotification => ['https://example.test', 4321, 'fr', 'claim-token', 'cg-guid'],
        NotificationType::MarketplaceNewInterestedProsNotification => ['https://example.test', 4321],
        NotificationType::MarketplaceAssignedProsReminderNotification => ['https://example.test', 4321],
        NotificationType::MarketplacePlatformFeedbackNotification => ['https://example.test', 4321],
        NotificationType::MarketplacePlatformFeedbackReminderNotification => ['https://example.test', 4321],
        NotificationType::MarketplaceProfileReviewReminderNotification => ['https://example.test', 'First Name', 'pro-1', 'Pro Name'],
        NotificationType::ToolsUnpaidLevel1Notification => ['03/11/2026', '08/11/2026', [['number' => 'F-2026-001', 'amount' => '1.234,56 €', 'date' => '01/09/2026']], '1.234,56 €', 'Trustup SA', 'BE68539007547034', null, '+++123/4567/89012+++', 'https://example.test/qr.png', 'https://example.test/invoices'],
        NotificationType::ToolsUnpaidLevel2Notification => ['03/11/2026', '08/11/2026', [['number' => 'F-2026-001', 'amount' => '1.234,56 €', 'date' => '01/09/2026']], '1.234,56 €', 'Trustup SA', 'BE68539007547034', null, '+++123/4567/89012+++', 'https://example.test/qr.png', 'https://example.test/invoices'],
        NotificationType::ToolsUnpaidLevel3Notification => ['03/11/2026', '08/11/2026', [['number' => 'F-2026-001', 'amount' => '1.234,56 €', 'date' => '01/09/2026']], '1.234,56 €', 'Trustup SA', 'BE68539007547034', null, '+++123/4567/89012+++', 'https://example.test/qr.png', 'https://example.test/invoices'],
        NotificationType::ToolsUnpaidLevel4Notification => ['03/11/2026', '08/11/2026', [['number' => 'F-2026-001', 'amount' => '1.234,56 €', 'date' => '01/09/2026']], '1.234,56 €', 'Trustup SA', 'BE68539007547034', null, '+++123/4567/89012+++', 'https://example.test/qr.png', 'https://example.test/invoices'],
        NotificationType::T360OnboardingInvitationNotification => ['email-uuid', 'Sujet', '<p>Bonjour</p>', 'Bonjour'],
        NotificationType::T360OnboardingConfirmationNotification => ['email-uuid', 'Sujet', '<p>Bonjour</p>', 'Bonjour'],
        NotificationType::T360OnboardingReminderNotification => ['email-uuid', 'Sujet', '<p>Bonjour</p>', 'Bonjour'],
        NotificationType::T360OnboardingRescheduledNotification => ['email-uuid', 'Sujet', '<p>Bonjour</p>', 'Bonjour'],
        NotificationType::T360OnboardingCancelledNotification => ['email-uuid', 'Sujet', '<p>Bonjour</p>', 'Bonjour'],
        NotificationType::T360OnboardingNoShowNotification => ['email-uuid', 'Sujet', '<p>Bonjour</p>', 'Bonjour'],
        NotificationType::ToolsPaymentPlanConfirmationNotification => ['plan-email-uuid', 'PLAN-2026-0042', '17/11/2026', [['position' => 1, 'due_on' => '01/12/2026', 'amount' => '250,00 €']], '250,00 €', 'Trustup SA', 'BE68539007547034', 'GKCCBEBB', '+++123/4567/89012+++'],
        NotificationType::ToolsPaymentPlanInstallmentDueNotification => ['plan-email-uuid', 'PLAN-2026-0042', 1, 3, '01/12/2026', '250,00 €', 'Trustup SA', 'BE68539007547034', 'GKCCBEBB', '+++123/4567/89012+++'],
        default => throw new LogicException("No minimal args defined for [{$type->value}] in coherence test."),
    };

    $instance = new $dataClass(...$minimalArgs);

    expect($instance->notificationType())->toBe($type);
})->with(NotificationType::cases());
