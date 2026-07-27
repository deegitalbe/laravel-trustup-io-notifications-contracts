<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Tests\Stubs;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\EmailCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\NotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\PushCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Contracts\SmsCapable;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersEmail;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersPush;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\RendersSms;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\SerializesFromConstructor;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

final readonly class OverriddenRenderingStub implements EmailCapable, NotificationData, PushCapable, SmsCapable
{
    use RendersEmail;
    use RendersPush;
    use RendersSms;
    use SerializesFromConstructor;

    public function __construct(public string $title, public string $body) {}

    public function notificationType(): NotificationType
    {
        return NotificationType::ToolsTestNotification;
    }

    public function emailTemplate(): string
    {
        return 'custom-alias';
    }

    protected function smsBody(): string
    {
        return 'custom sms';
    }

    protected function pushTitle(): string
    {
        return 'custom title';
    }

    protected function pushBody(): string
    {
        return 'custom body';
    }

    /** @return array<string, mixed> */
    protected function pushData(): array
    {
        return ['custom' => true];
    }
}
