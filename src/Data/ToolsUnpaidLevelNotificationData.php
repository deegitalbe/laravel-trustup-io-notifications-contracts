<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\EmailCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersEmail;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\SerializesFromConstructor;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\EmailTemplateLocaleGranularity;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\SenderRole;

abstract readonly class ToolsUnpaidLevelNotificationData implements EmailCapable, NotificationData
{
    use RendersEmail;
    use SerializesFromConstructor;

    /** @param array<int, array{number: string, amount: string, date: string}> $invoices */
    public function __construct(
        public string $statement_date,
        public string $payment_due_date,
        public array $invoices,
        public string $total,
        public string $beneficiary,
        public string $iban,
        public ?string $bic,
        public string $communication,
        public string $qr_code_url,
        public string $invoices_url,
    ) {}

    public function emailSender(): EmailSender
    {
        return new EmailSender(SenderRole::CreditControl, 'TrustUp');
    }

    public function emailTemplateLocaleGranularity(): EmailTemplateLocaleGranularity
    {
        return EmailTemplateLocaleGranularity::Locale;
    }
}
