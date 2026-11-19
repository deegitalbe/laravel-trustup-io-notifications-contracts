<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\EmailCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersEmail;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\SerializesFromConstructor;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\SenderRole;

final readonly class ToolsPaymentPlanConfirmationNotificationData implements EmailCapable, NotificationData
{
    use RendersEmail;
    use SerializesFromConstructor;

    /** @param array<int, array{position: int, due_on: string, amount: string}> $installments */
    public function __construct(
        public string $plan_email_id,
        public string $plan_code,
        public string $created_on,
        public array $installments,
        public string $total,
        public string $beneficiary,
        public string $iban,
        public string $bic,
        public string $reference,
    ) {}

    public function notificationType(): NotificationType
    {
        return NotificationType::ToolsPaymentPlanConfirmationNotification;
    }

    public function emailSender(): EmailSender
    {
        return new EmailSender(SenderRole::CreditControl);
    }
}
