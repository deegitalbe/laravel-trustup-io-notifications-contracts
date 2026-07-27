<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns;

use Deegitalbe\TrustupIoNotificationsContracts\Data\SmsContent;

/**
 * Default SmsCapable rendering. Unlike email, SMS has no provider-side
 * template, so the body is translated service-side via __(). Key defaults to
 * "notifications.{type}.sms", placeholders to every constructor property.
 *
 * Override granularity: smsBody() for the final text, smsTranslationKey() for
 * the key, smsBodyTranslationParams() for the placeholders.
 *
 * Expects notificationType() and toArray() on the using class.
 */
trait RendersSms
{
    public function toSms(): SmsContent
    {
        return new SmsContent($this->smsBody());
    }

    protected function smsBody(): string
    {
        return (string) __($this->smsTranslationKey(), $this->smsBodyTranslationParams());
    }

    protected function smsTranslationKey(): string
    {
        return "notifications.{$this->notificationType()->slug()}.sms";
    }

    /** @return array<string, mixed> */
    protected function smsBodyTranslationParams(): array
    {
        return $this->toArray();
    }
}
