<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\EmailCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersEmail;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\SerializesFromConstructor;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\SenderRole;

abstract readonly class T360OnboardingNotificationData implements EmailCapable, NotificationData
{
    use RendersEmail;
    use SerializesFromConstructor;

    public function __construct(
        public string $onboarding_email_id,
        public string $subject,
        public string $html_body,
        public string $text_body,
    ) {}

    public function emailTemplate(): string
    {
        return 't360-onboarding-notification';
    }

    public function emailSender(): EmailSender
    {
        return new EmailSender(SenderRole::Onboarding, 'Trustup Onboarding');
    }
}
