<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns;

use Deegitalbe\TrustupIoNotificationsContracts\Data\EmailContent;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\EmailTemplateLocaleGranularity;

/**
 * Default EmailCapable rendering. Email is rendered provider-side (the Postmark
 * template owns layout and per-language wording), so the content is just the
 * Postmark template model, defaulting to every constructor property. The
 * template name defaults to the notification type value in kebab-case (dots to
 * dashes). Override emailVariables() or emailTemplate() when the defaults do
 * not fit.
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

    /** @return array<string, mixed> */
    protected function emailVariables(): array
    {
        return $this->toArray();
    }
}
