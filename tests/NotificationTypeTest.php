<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceAssignationActivationNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceDemandReceivedNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceNewChatMessageForCustomerNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceReviewRequestNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceSatisfactionSurveyNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceUnclaimedDemandReminderNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceUserAssignmentNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceUserReassignNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\T360OnboardingCancelledNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\T360OnboardingConfirmationNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\T360OnboardingInvitationNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\T360OnboardingNoShowNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\T360OnboardingReminderNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\T360OnboardingRescheduledNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsNewChatMessageForProfessionalNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsNewDemandForProfessionalFreemiumNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsPaymentPlanConfirmationNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsPaymentPlanInstallmentDueNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsProResponseReminderNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsProSecondReminderNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsTestNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\WhitelabelDemandReceivedNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\WhitelabelNewChatMessageNotificationData;
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

it('NotificationType::MarketplaceUnclaimedDemandReminderNotification maps to Marketplace source, its slug and data class', function (): void {
    $type = NotificationType::MarketplaceUnclaimedDemandReminderNotification;

    expect($type->source())->toBe(Source::Marketplace);
    expect($type->slug())->toBe('marketplace-unclaimed-demand-reminder-notification');
    expect($type->dataClass())->toBe(MarketplaceUnclaimedDemandReminderNotificationData::class);
});

it('NotificationType::MarketplaceUserAssignmentNotification maps to Marketplace source, its slug and data class', function (): void {
    $type = NotificationType::MarketplaceUserAssignmentNotification;

    expect($type->source())->toBe(Source::Marketplace);
    expect($type->slug())->toBe('marketplace-user-assignment-notification');
    expect($type->dataClass())->toBe(MarketplaceUserAssignmentNotificationData::class);
});

it('NotificationType::MarketplaceSatisfactionSurveyNotification maps to Marketplace source, its slug and data class', function (): void {
    $type = NotificationType::MarketplaceSatisfactionSurveyNotification;

    expect($type->source())->toBe(Source::Marketplace);
    expect($type->slug())->toBe('marketplace-satisfaction-survey-notification');
    expect($type->dataClass())->toBe(MarketplaceSatisfactionSurveyNotificationData::class);
});

it('NotificationType::ToolsProResponseReminderNotification maps to Tools source, its slug and data class', function (): void {
    $type = NotificationType::ToolsProResponseReminderNotification;

    expect($type->source())->toBe(Source::Tools);
    expect($type->slug())->toBe('tools-pro-response-reminder-notification');
    expect($type->dataClass())->toBe(ToolsProResponseReminderNotificationData::class);
});

it('NotificationType::ToolsProSecondReminderNotification maps to Tools source, its slug and data class', function (): void {
    $type = NotificationType::ToolsProSecondReminderNotification;

    expect($type->source())->toBe(Source::Tools);
    expect($type->slug())->toBe('tools-pro-second-reminder-notification');
    expect($type->dataClass())->toBe(ToolsProSecondReminderNotificationData::class);
});

it('NotificationType::ToolsNewChatMessageForProfessionalNotification maps to Tools source, its slug and data class', function (): void {
    $type = NotificationType::ToolsNewChatMessageForProfessionalNotification;

    expect($type->source())->toBe(Source::Tools);
    expect($type->slug())->toBe('tools-new-chat-message-for-professional-notification');
    expect($type->dataClass())->toBe(ToolsNewChatMessageForProfessionalNotificationData::class);
});

it('NotificationType::MarketplaceUserReassignNotification maps to Marketplace source, its slug and data class', function (): void {
    $type = NotificationType::MarketplaceUserReassignNotification;

    expect($type->source())->toBe(Source::Marketplace);
    expect($type->slug())->toBe('marketplace-user-reassign-notification');
    expect($type->dataClass())->toBe(MarketplaceUserReassignNotificationData::class);
});

it('NotificationType::MarketplaceAssignationActivationNotification maps to Marketplace source, its slug and data class', function (): void {
    $type = NotificationType::MarketplaceAssignationActivationNotification;

    expect($type->value)->toBe('marketplace.assignation-activation.notification');
    expect($type->source())->toBe(Source::Marketplace);
    expect($type->slug())->toBe('marketplace-assignation-activation-notification');
    expect($type->dataClass())->toBe(MarketplaceAssignationActivationNotificationData::class);
});

it('NotificationType::MarketplaceNewChatMessageForCustomerNotification maps to Marketplace source, its slug and data class', function (): void {
    $type = NotificationType::MarketplaceNewChatMessageForCustomerNotification;

    expect($type->source())->toBe(Source::Marketplace);
    expect($type->slug())->toBe('marketplace-new-chat-message-for-customer-notification');
    expect($type->dataClass())->toBe(MarketplaceNewChatMessageForCustomerNotificationData::class);
});

it('NotificationType::ToolsNewDemandForProfessionalFreemiumNotification maps to Tools source, its slug and data class', function (): void {
    $type = NotificationType::ToolsNewDemandForProfessionalFreemiumNotification;

    expect($type->value)->toBe('tools.new-demand-for-professional-freemium.notification');
    expect($type->source())->toBe(Source::Tools);
    expect($type->slug())->toBe('tools-new-demand-for-professional-freemium-notification');
    expect($type->dataClass())->toBe(ToolsNewDemandForProfessionalFreemiumNotificationData::class);
});

it('NotificationType::WhitelabelDemandReceivedNotification maps to Marketplace source, its slug and data class', function (): void {
    $type = NotificationType::WhitelabelDemandReceivedNotification;

    expect($type->value)->toBe('marketplace.whitelabel-demand-received.notification');
    expect($type->source())->toBe(Source::Marketplace);
    expect($type->slug())->toBe('marketplace-whitelabel-demand-received-notification');
    expect($type->dataClass())->toBe(WhitelabelDemandReceivedNotificationData::class);
});

it('NotificationType::WhitelabelNewChatMessageNotification maps to Marketplace source, its slug and data class', function (): void {
    $type = NotificationType::WhitelabelNewChatMessageNotification;

    expect($type->value)->toBe('marketplace.whitelabel-new-chat-message.notification');
    expect($type->source())->toBe(Source::Marketplace);
    expect($type->slug())->toBe('marketplace-whitelabel-new-chat-message-notification');
    expect($type->dataClass())->toBe(WhitelabelNewChatMessageNotificationData::class);
});

it('maps each V3 marketplace type to Marketplace source, its slug and data class', function (NotificationType $type, string $value, string $slug, string $dataClass): void {
    expect($type->value)->toBe($value);
    expect($type->source())->toBe(Source::Marketplace);
    expect($type->slug())->toBe($slug);
    expect($type->dataClass())->toBe($dataClass);
})->with([
    'new interested pros' => [NotificationType::MarketplaceNewInterestedProsNotification, 'marketplace.new-interested-pros.notification', 'marketplace-new-interested-pros-notification', Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceNewInterestedProsNotificationData::class],
    'assigned pros reminder' => [NotificationType::MarketplaceAssignedProsReminderNotification, 'marketplace.assigned-pros-reminder.notification', 'marketplace-assigned-pros-reminder-notification', Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceAssignedProsReminderNotificationData::class],
    'platform feedback' => [NotificationType::MarketplacePlatformFeedbackNotification, 'marketplace.platform-feedback.notification', 'marketplace-platform-feedback-notification', Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplacePlatformFeedbackNotificationData::class],
    'platform feedback reminder' => [NotificationType::MarketplacePlatformFeedbackReminderNotification, 'marketplace.platform-feedback-reminder.notification', 'marketplace-platform-feedback-reminder-notification', Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplacePlatformFeedbackReminderNotificationData::class],
    'profile review reminder' => [NotificationType::MarketplaceProfileReviewReminderNotification, 'marketplace.profile-review-reminder.notification', 'marketplace-profile-review-reminder-notification', Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceProfileReviewReminderNotificationData::class],
]);

it('maps each unpaid level type to Tools source, its slug and data class', function (NotificationType $type, string $value, string $slug, string $dataClass): void {
    expect($type->value)->toBe($value);
    expect($type->source())->toBe(Source::Tools);
    expect($type->slug())->toBe($slug);
    expect($type->dataClass())->toBe($dataClass);
})->with([
    'unpaid level 1' => [NotificationType::ToolsUnpaidLevel1Notification, 'tools.unpaid-level-1.notification', 'tools-unpaid-level-1-notification', Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsUnpaidLevel1NotificationData::class],
    'unpaid level 2' => [NotificationType::ToolsUnpaidLevel2Notification, 'tools.unpaid-level-2.notification', 'tools-unpaid-level-2-notification', Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsUnpaidLevel2NotificationData::class],
    'unpaid level 3' => [NotificationType::ToolsUnpaidLevel3Notification, 'tools.unpaid-level-3.notification', 'tools-unpaid-level-3-notification', Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsUnpaidLevel3NotificationData::class],
    'unpaid level 4' => [NotificationType::ToolsUnpaidLevel4Notification, 'tools.unpaid-level-4.notification', 'tools-unpaid-level-4-notification', Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsUnpaidLevel4NotificationData::class],
]);

it('maps each t360 onboarding type to the t360 source, its slug and data class', function (NotificationType $type, string $slug, string $dataClass): void {
    expect($type->source())->toBe(Source::T360)
        ->and($type->slug())->toBe($slug)
        ->and($type->dataClass())->toBe($dataClass);
})->with([
    'invitation' => [NotificationType::T360OnboardingInvitationNotification, 't360-onboarding-invitation-notification', T360OnboardingInvitationNotificationData::class],
    'confirmation' => [NotificationType::T360OnboardingConfirmationNotification, 't360-onboarding-confirmation-notification', T360OnboardingConfirmationNotificationData::class],
    'reminder' => [NotificationType::T360OnboardingReminderNotification, 't360-onboarding-reminder-notification', T360OnboardingReminderNotificationData::class],
    'rescheduled' => [NotificationType::T360OnboardingRescheduledNotification, 't360-onboarding-rescheduled-notification', T360OnboardingRescheduledNotificationData::class],
    'cancelled' => [NotificationType::T360OnboardingCancelledNotification, 't360-onboarding-cancelled-notification', T360OnboardingCancelledNotificationData::class],
    'noshow' => [NotificationType::T360OnboardingNoShowNotification, 't360-onboarding-no-show-notification', T360OnboardingNoShowNotificationData::class],
]);

it('maps each payment plan type to the t360 source, its tools slug and data class', function (NotificationType $type, string $value, string $slug, string $dataClass): void {
    expect($type->value)->toBe($value)
        ->and($type->source())->toBe(Source::T360)
        ->and($type->slug())->toBe($slug)
        ->and($type->dataClass())->toBe($dataClass);
})->with([
    'confirmation' => [NotificationType::ToolsPaymentPlanConfirmationNotification, 'tools.payment-plan-confirmation.notification', 'tools-payment-plan-confirmation-notification', ToolsPaymentPlanConfirmationNotificationData::class],
    'installment due' => [NotificationType::ToolsPaymentPlanInstallmentDueNotification, 'tools.payment-plan-installment-due.notification', 'tools-payment-plan-installment-due-notification', ToolsPaymentPlanInstallmentDueNotificationData::class],
]);
