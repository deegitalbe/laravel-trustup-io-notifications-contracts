<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Enums;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\EmailCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\PushCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\SmsCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceAssignationActivationNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceDemandReceivedNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceDemandTransmittedNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceNewChatMessageForCustomerNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceReviewRequestNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceSatisfactionSurveyNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceUserReassignNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsFullLocaleTestNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsNewChatMessageForProfessionalNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsNewDemandNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsProResponseReminderNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsTestNotificationData;
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

    case MarketplaceSatisfactionSurveyNotification = 'marketplace.satisfaction-survey.notification';

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
            // PHPStan narrows $this to exactly this case once every other case is listed above, so it
            // flags this comparison as match.alwaysTrue; the `default => throw` arm is kept anyway as a
            // safety net for any future case added without a mapping, hence the ignore below.
            self::MarketplaceSatisfactionSurveyNotification => MarketplaceSatisfactionSurveyNotificationData::class, // @phpstan-ignore-line
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
            // Same match.alwaysTrue reasoning as in dataClass() above: PHPStan considers this last
            // case redundant, but the `default => throw` safety net justifies keeping it explicit.
            self::MarketplaceSatisfactionSurveyNotification => Source::Marketplace, // @phpstan-ignore-line
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
