<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns;

use Deegitalbe\TrustupIoNotificationsContracts\Data\EmailContent;
use Deegitalbe\TrustupIoNotificationsContracts\Data\EmailSender;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\EmailTemplateLocaleGranularity;

/**
 * Default EmailCapable rendering. Email is rendered provider-side (the Postmark
 * template owns layout and per-language wording), so the content is just the
 * Postmark template model, defaulting to every constructor property. The
 * template name defaults to the notification type value in kebab-case (dots to
 * dashes). The sender defaults to the source's default address with no display
 * name. Override emailVariables(), emailTemplate() or emailSender() when the
 * defaults do not fit.
 *
 * Expects notificationType() and toArray() (e.g. via SerializesFromConstructor).
 */
trait RendersEmail
{
    public function toEmail(): EmailContent
    {
        return new EmailContent($this->emailVariables());
    }

    public function emailTemplate(): string
    {
        return $this->notificationType()->slug();
    }

    public function emailTemplateLocaleGranularity(): EmailTemplateLocaleGranularity
    {
        return EmailTemplateLocaleGranularity::Language;
    }

    public function emailSender(): EmailSender
    {
        return new EmailSender;
    }

    /** @return array<string, mixed> */
    protected function emailVariables(): array
    {
        return $this->toArray();
    }
}
