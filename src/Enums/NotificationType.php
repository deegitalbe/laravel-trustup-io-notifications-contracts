<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Enums;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\EmailCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\PushCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\SmsCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceAssignationActivationNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceAssignedProsReminderNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceDemandReceivedNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceDemandTransmittedNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceNewChatMessageForCustomerNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceNewInterestedProsNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplacePlatformFeedbackNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplacePlatformFeedbackReminderNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceProfileReviewReminderNotificationData;
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
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsFullLocaleTestNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsNewChatMessageForProfessionalNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsNewDemandForProfessionalFreemiumNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsNewDemandForProfessionalNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsNewDemandNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsPaymentPlanConfirmationNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsPaymentPlanInstallmentDueNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsProResponseReminderNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsProSecondReminderNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsTestNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsUnpaidLevel1NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsUnpaidLevel2NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsUnpaidLevel3NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsUnpaidLevel4NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\WhitelabelDemandReceivedNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\WhitelabelNewChatMessageNotificationData;
use LogicException;

enum NotificationType: string
{
    case ToolsTestNotification = 'tools.test.notification';

    /** Test fixture that opts into full-locale granularity for email templates. */
    case ToolsFullLocaleTestNotification = 'tools.full-locale.test.notification';

    case MarketplaceReviewRequestNotification = 'marketplace.review-request.notification';

    case ToolsNewDemandNotification = 'tools.new-demand.notification';

    case MarketplaceDemandTransmittedNotification = 'marketplace.demand-transmitted.notification';

    case MarketplaceDemandReceivedNotification = 'marketplace.demand-received.notification';

    case ToolsNewChatMessageForProfessionalNotification = 'tools.new-chat-message-for-professional.notification';

    case MarketplaceUserReassignNotification = 'marketplace.user-reassign.notification';

    case MarketplaceAssignationActivationNotification = 'marketplace.assignation-activation.notification';

    case MarketplaceNewChatMessageForCustomerNotification = 'marketplace.new-chat-message-for-customer.notification';

    case ToolsProResponseReminderNotification = 'tools.pro-response-reminder.notification';

    case ToolsProSecondReminderNotification = 'tools.pro-second-reminder.notification';

    case MarketplaceSatisfactionSurveyNotification = 'marketplace.satisfaction-survey.notification';

    case ToolsNewDemandForProfessionalNotification = 'tools.new-demand-for-professional.notification';

    case ToolsNewDemandForProfessionalFreemiumNotification = 'tools.new-demand-for-professional-freemium.notification';

    case MarketplaceUserAssignmentNotification = 'marketplace.user-assignment.notification';

    case MarketplaceUnclaimedDemandReminderNotification = 'marketplace.unclaimed-demand-reminder.notification';

    case WhitelabelDemandReceivedNotification = 'marketplace.whitelabel-demand-received.notification';

    case WhitelabelNewChatMessageNotification = 'marketplace.whitelabel-new-chat-message.notification';

    case MarketplaceNewInterestedProsNotification = 'marketplace.new-interested-pros.notification';

    case MarketplaceAssignedProsReminderNotification = 'marketplace.assigned-pros-reminder.notification';

    case MarketplacePlatformFeedbackNotification = 'marketplace.platform-feedback.notification';

    case MarketplacePlatformFeedbackReminderNotification = 'marketplace.platform-feedback-reminder.notification';

    case MarketplaceProfileReviewReminderNotification = 'marketplace.profile-review-reminder.notification';

    case ToolsUnpaidLevel1Notification = 'tools.unpaid-level-1.notification';
    case ToolsUnpaidLevel2Notification = 'tools.unpaid-level-2.notification';
    case ToolsUnpaidLevel3Notification = 'tools.unpaid-level-3.notification';
    case ToolsUnpaidLevel4Notification = 'tools.unpaid-level-4.notification';

    case T360OnboardingInvitationNotification = 't360.onboarding-invitation.notification';
    case T360OnboardingConfirmationNotification = 't360.onboarding-confirmation.notification';
    case T360OnboardingReminderNotification = 't360.onboarding-reminder.notification';
    case T360OnboardingRescheduledNotification = 't360.onboarding-rescheduled.notification';
    case T360OnboardingCancelledNotification = 't360.onboarding-cancelled.notification';
    case T360OnboardingNoShowNotification = 't360.onboarding-no-show.notification';

    // Keep the tools. prefix: the slug must give back the existing 360 Postmark aliases; source() maps these to T360, not the prefix.
    case ToolsPaymentPlanConfirmationNotification = 'tools.payment-plan-confirmation.notification';
    case ToolsPaymentPlanInstallmentDueNotification = 'tools.payment-plan-installment-due.notification';

    /**
     * Dotless form of the value ("tools-test-notification"), safe to embed in a
     * dotted translation key or a provider template name.
     */
    public function slug(): string
    {
        return str_replace('.', '-', $this->value);
    }

    public function dataClass(): string
    {
        return match ($this) {
            self::ToolsTestNotification => ToolsTestNotificationData::class,
            self::ToolsFullLocaleTestNotification => ToolsFullLocaleTestNotificationData::class,
            self::MarketplaceReviewRequestNotification => MarketplaceReviewRequestNotificationData::class,
            self::ToolsNewDemandNotification => ToolsNewDemandNotificationData::class,
            self::MarketplaceDemandTransmittedNotification => MarketplaceDemandTransmittedNotificationData::class,
            self::MarketplaceDemandReceivedNotification => MarketplaceDemandReceivedNotificationData::class,
            self::ToolsNewChatMessageForProfessionalNotification => ToolsNewChatMessageForProfessionalNotificationData::class,
            self::MarketplaceUserReassignNotification => MarketplaceUserReassignNotificationData::class,
            self::MarketplaceAssignationActivationNotification => MarketplaceAssignationActivationNotificationData::class,
            self::MarketplaceNewChatMessageForCustomerNotification => MarketplaceNewChatMessageForCustomerNotificationData::class,
            self::ToolsProResponseReminderNotification => ToolsProResponseReminderNotificationData::class,
            self::ToolsProSecondReminderNotification => ToolsProSecondReminderNotificationData::class,
            self::MarketplaceSatisfactionSurveyNotification => MarketplaceSatisfactionSurveyNotificationData::class,
            self::ToolsNewDemandForProfessionalNotification => ToolsNewDemandForProfessionalNotificationData::class,
            self::ToolsNewDemandForProfessionalFreemiumNotification => ToolsNewDemandForProfessionalFreemiumNotificationData::class,
            self::MarketplaceUserAssignmentNotification => MarketplaceUserAssignmentNotificationData::class,
            self::MarketplaceUnclaimedDemandReminderNotification => MarketplaceUnclaimedDemandReminderNotificationData::class,
            self::WhitelabelDemandReceivedNotification => WhitelabelDemandReceivedNotificationData::class,
            self::WhitelabelNewChatMessageNotification => WhitelabelNewChatMessageNotificationData::class,
            self::MarketplaceNewInterestedProsNotification => MarketplaceNewInterestedProsNotificationData::class,
            self::MarketplaceAssignedProsReminderNotification => MarketplaceAssignedProsReminderNotificationData::class,
            self::MarketplacePlatformFeedbackNotification => MarketplacePlatformFeedbackNotificationData::class,
            self::MarketplacePlatformFeedbackReminderNotification => MarketplacePlatformFeedbackReminderNotificationData::class,
            self::MarketplaceProfileReviewReminderNotification => MarketplaceProfileReviewReminderNotificationData::class,
            self::ToolsUnpaidLevel1Notification => ToolsUnpaidLevel1NotificationData::class,
            self::ToolsUnpaidLevel2Notification => ToolsUnpaidLevel2NotificationData::class,
            self::ToolsUnpaidLevel3Notification => ToolsUnpaidLevel3NotificationData::class,
            self::ToolsUnpaidLevel4Notification => ToolsUnpaidLevel4NotificationData::class,
            self::T360OnboardingInvitationNotification => T360OnboardingInvitationNotificationData::class,
            self::T360OnboardingConfirmationNotification => T360OnboardingConfirmationNotificationData::class,
            self::T360OnboardingReminderNotification => T360OnboardingReminderNotificationData::class,
            self::T360OnboardingRescheduledNotification => T360OnboardingRescheduledNotificationData::class,
            self::T360OnboardingCancelledNotification => T360OnboardingCancelledNotificationData::class,
            self::T360OnboardingNoShowNotification => T360OnboardingNoShowNotificationData::class,
            self::ToolsPaymentPlanConfirmationNotification => ToolsPaymentPlanConfirmationNotificationData::class,
            // PHPStan narrows $this to exactly this case once every other case is listed above, so it
            // flags this comparison as match.alwaysTrue; the `default => throw` arm is kept anyway as a
            // safety net for any future case added without a mapping, hence the ignore below.
            self::ToolsPaymentPlanInstallmentDueNotification => ToolsPaymentPlanInstallmentDueNotificationData::class, // @phpstan-ignore-line
            default => throw new LogicException("NotificationType [{$this->value}] has no dataClass mapping."),
        };
    }

    public function source(): Source
    {
        return match ($this) {
            self::ToolsTestNotification => Source::Tools,
            self::ToolsFullLocaleTestNotification => Source::Tools,
            self::MarketplaceReviewRequestNotification => Source::Marketplace,
            self::ToolsNewDemandNotification => Source::Tools,
            self::MarketplaceDemandTransmittedNotification => Source::Marketplace,
            self::MarketplaceDemandReceivedNotification => Source::Marketplace,
            self::ToolsNewChatMessageForProfessionalNotification => Source::Tools,
            self::MarketplaceUserReassignNotification => Source::Marketplace,
            self::MarketplaceAssignationActivationNotification => Source::Marketplace,
            self::MarketplaceNewChatMessageForCustomerNotification => Source::Marketplace,
            self::ToolsProResponseReminderNotification => Source::Tools,
            self::ToolsProSecondReminderNotification => Source::Tools,
            self::MarketplaceSatisfactionSurveyNotification => Source::Marketplace,
            self::ToolsNewDemandForProfessionalNotification => Source::Tools,
            self::ToolsNewDemandForProfessionalFreemiumNotification => Source::Tools,
            self::MarketplaceUserAssignmentNotification => Source::Marketplace,
            self::MarketplaceUnclaimedDemandReminderNotification => Source::Marketplace,
            self::WhitelabelDemandReceivedNotification => Source::Marketplace,
            self::WhitelabelNewChatMessageNotification => Source::Marketplace,
            self::MarketplaceNewInterestedProsNotification => Source::Marketplace,
            self::MarketplaceAssignedProsReminderNotification => Source::Marketplace,
            self::MarketplacePlatformFeedbackNotification => Source::Marketplace,
            self::MarketplacePlatformFeedbackReminderNotification => Source::Marketplace,
            self::MarketplaceProfileReviewReminderNotification => Source::Marketplace,
            self::ToolsUnpaidLevel1Notification => Source::Tools,
            self::ToolsUnpaidLevel2Notification => Source::Tools,
            self::ToolsUnpaidLevel3Notification => Source::Tools,
            self::ToolsUnpaidLevel4Notification => Source::Tools,
            self::T360OnboardingInvitationNotification => Source::T360,
            self::T360OnboardingConfirmationNotification => Source::T360,
            self::T360OnboardingReminderNotification => Source::T360,
            self::T360OnboardingRescheduledNotification => Source::T360,
            self::T360OnboardingCancelledNotification => Source::T360,
            self::T360OnboardingNoShowNotification => Source::T360,
            self::ToolsPaymentPlanConfirmationNotification => Source::T360,
            // Same match.alwaysTrue reasoning as in dataClass() above: PHPStan considers this last
            // case redundant, but the `default => throw` safety net justifies keeping it explicit.
            self::ToolsPaymentPlanInstallmentDueNotification => Source::T360, // @phpstan-ignore-line
            default => throw new LogicException("NotificationType [{$this->value}] has no source mapping."),
        };
    }

    /** @return array<int, NotificationChannel> */
    public function supportedChannels(): array
    {
        $dataClass = $this->dataClass();
        $channels = [];

        if (is_a($dataClass, EmailCapable::class, true)) {
            $channels[] = NotificationChannel::Email;
        }

        if (is_a($dataClass, SmsCapable::class, true)) {
            $channels[] = NotificationChannel::Sms;
        }

        if (is_a($dataClass, PushCapable::class, true)) {
            $channels[] = NotificationChannel::Push;
        }

        return $channels;
    }

    /** @return array<int, self> */
    public static function forSource(Source $source): array
    {
        return array_values(array_filter(
            self::cases(),
            fn (self $type): bool => $type->source() === $source,
        ));
    }
}
