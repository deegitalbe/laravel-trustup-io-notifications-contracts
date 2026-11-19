<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\T360OnboardingInvitationNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsTestNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Engagement\EngagementPayload;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\ChannelEventKind;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationChannel;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationStatus;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Status\StatusPayload;

function onboardingInvitationData(): T360OnboardingInvitationNotificationData
{
    return new T360OnboardingInvitationNotificationData(
        onboarding_email_id: '0b8f5c1e-2f4a-4d0e-9a51-6f1d2c3b4a5e',
        subject: 'Votre onboarding',
        html_body: '<p>Bonjour</p>',
        text_body: 'Bonjour',
    );
}

it('serializes provider_message_id and failure_detail on a status, null by default', function (): void {
    $payload = new StatusPayload(
        sendId: 'send-1',
        channel: NotificationChannel::Email,
        status: NotificationStatus::Pending,
        type: NotificationType::ToolsTestNotification,
        data: new ToolsTestNotificationData('https://example.test', 'T', 'B'),
    );

    expect($payload->toArray())
        ->toHaveKey('provider_message_id', null)
        ->toHaveKey('failure_detail', null);
});

it('round-trips provider_message_id and failure_detail on a status', function (): void {
    $payload = new StatusPayload(
        sendId: 'send-1',
        channel: NotificationChannel::Email,
        status: NotificationStatus::Error,
        type: NotificationType::ToolsTestNotification,
        data: new ToolsTestNotificationData('https://example.test', 'T', 'B'),
        kind: ChannelEventKind::Bounced,
        failureReason: 'HardBounce',
        providerMessageId: 'b7bc2f4a-e38e-4336-af7d-e6c392c2f817',
        failureDetail: 'The server was unable to deliver your message.',
    );

    $restored = StatusPayload::fromArray($payload->toArray());

    expect($restored->providerMessageId)->toBe('b7bc2f4a-e38e-4336-af7d-e6c392c2f817')
        ->and($restored->failureDetail)->toBe('The server was unable to deliver your message.');
});

it('reads a status without the new keys as null', function (): void {
    $restored = StatusPayload::fromArray([
        'send_id' => 'send-1',
        'channel' => 'email',
        'status' => 'sent',
        'type' => NotificationType::ToolsTestNotification->value,
        'data' => ['base_url' => 'https://example.test', 'title' => 'T', 'body' => 'B'],
    ]);

    expect($restored->providerMessageId)->toBeNull()
        ->and($restored->failureDetail)->toBeNull();
});

it('leaves the onboarding bodies out of a status data', function (): void {
    $payload = new StatusPayload(
        sendId: 'send-1',
        channel: NotificationChannel::Email,
        status: NotificationStatus::Sent,
        type: NotificationType::T360OnboardingInvitationNotification,
        data: onboardingInvitationData(),
    );

    expect($payload->toArray()['data'])->toBe([
        'onboarding_email_id' => '0b8f5c1e-2f4a-4d0e-9a51-6f1d2c3b4a5e',
        'subject' => 'Votre onboarding',
    ]);
});

it('decodes an onboarding status whose data carries no bodies', function (): void {
    $payload = new StatusPayload(
        sendId: 'send-1',
        channel: NotificationChannel::Email,
        status: NotificationStatus::Sent,
        type: NotificationType::T360OnboardingInvitationNotification,
        data: onboardingInvitationData(),
    );

    $restored = StatusPayload::fromArray($payload->toArray());

    expect($restored->data)->toBeInstanceOf(T360OnboardingInvitationNotificationData::class)
        ->and($restored->data->toArray())->toBe([
            'onboarding_email_id' => '0b8f5c1e-2f4a-4d0e-9a51-6f1d2c3b4a5e',
            'subject' => 'Votre onboarding',
            'html_body' => '',
            'text_body' => '',
        ]);
});

it('serializes and round-trips provider_message_id on an engagement', function (): void {
    $payload = new EngagementPayload(
        sendId: 'send-1',
        channel: NotificationChannel::Email,
        kind: ChannelEventKind::Opened,
        type: NotificationType::ToolsTestNotification,
        data: new ToolsTestNotificationData('https://example.test', 'T', 'B'),
        clickedUrl: null,
        providerMessageId: 'b7bc2f4a-e38e-4336-af7d-e6c392c2f817',
    );

    expect($payload->toArray())->toHaveKey('provider_message_id', 'b7bc2f4a-e38e-4336-af7d-e6c392c2f817')
        ->and(EngagementPayload::fromArray($payload->toArray())->providerMessageId)->toBe('b7bc2f4a-e38e-4336-af7d-e6c392c2f817');
});

it('leaves the onboarding bodies out of an engagement data and decodes it back', function (): void {
    $payload = new EngagementPayload(
        sendId: 'send-1',
        channel: NotificationChannel::Email,
        kind: ChannelEventKind::Clicked,
        type: NotificationType::T360OnboardingInvitationNotification,
        data: onboardingInvitationData(),
        clickedUrl: 'https://example.test',
    );

    $wire = $payload->toArray();

    expect($wire['data'])->not->toHaveKeys(['html_body', 'text_body'])
        ->and(EngagementPayload::fromArray($wire)->data)->toBeInstanceOf(T360OnboardingInvitationNotificationData::class);
});
