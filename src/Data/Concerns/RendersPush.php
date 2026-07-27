<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns;

use Deegitalbe\TrustupIoNotificationsContracts\Data\PushContent;

/**
 * Default PushCapable rendering. Like SMS, push has no provider-side template,
 * so title and body are translated service-side via __(). Keys default to
 * "notifications.{type}.push.{title,body}", the data payload and placeholders
 * to every constructor property.
 *
 * Override granularity: pushTitle()/pushBody() for the final text, pushData()
 * for the payload, pushTranslationKey() for the base key, and
 * pushTitleTranslationParams()/pushBodyTranslationParams() per output.
 *
 * Expects notificationType() and toArray() on the using class.
 */
trait RendersPush
{
    public function toPush(): PushContent
    {
        return new PushContent($this->pushTitle(), $this->pushBody(), $this->pushData());
    }

    protected function pushTitle(): string
    {
        return (string) __("{$this->pushTranslationKey()}.title", $this->pushTitleTranslationParams());
    }

    protected function pushBody(): string
    {
        return (string) __("{$this->pushTranslationKey()}.body", $this->pushBodyTranslationParams());
    }

    /** @return array<string, mixed> */
    protected function pushData(): array
    {
        return $this->toArray();
    }

    protected function pushTranslationKey(): string
    {
        return "notifications.{$this->notificationType()->slug()}.push";
    }

    /** @return array<string, mixed> */
    protected function pushTitleTranslationParams(): array
    {
        return $this->toArray();
    }

    /** @return array<string, mixed> */
    protected function pushBodyTranslationParams(): array
    {
        return $this->toArray();
    }
}
