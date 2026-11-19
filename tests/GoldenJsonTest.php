<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\T360OnboardingConfirmationNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\T360OnboardingInvitationNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsPaymentPlanInstallmentDueNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Engagement\EngagementPayload;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\ChannelEventKind;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\EventDirection;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationChannel;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationStatus;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Envelope;
use Deegitalbe\TrustupIoNotificationsContracts\Serialization\EnvelopeSerializer;
use Deegitalbe\TrustupIoNotificationsContracts\Status\StatusPayload;

dataset('golden envelopes', [
    'status sent' => [
        'status-sent.json',
        fn (): Envelope => new Envelope(
            version: Envelope::CURRENT_VERSION,
            direction: EventDirection::Status,
            payload: new StatusPayload(
                sendId: '01JD7ZK3M4N5P6Q7R8S9T0V1W2',
                channel: NotificationChannel::Email,
                status: NotificationStatus::Sent,
                type: NotificationType::T360OnboardingInvitationNotification,
                data: new T360OnboardingInvitationNotificationData(
                    onboarding_email_id: '0b8f5c1e-2f4a-4d0e-9a51-6f1d2c3b4a5e',
                    subject: 'Votre onboarding Trustup',
                    html_body: '<p>Bonjour</p>',
                    text_body: 'Bonjour',
                ),
                eventId: '6f1d2c3b-4a5e-4d0e-9a51-0b8f5c1e2f4a',
                sendEventId: '1024',
                occurredAt: '2026-11-18T09:15:02+00:00',
                providerMessageId: 'b7bc2f4a-e38e-4336-af7d-e6c392c2f817',
            ),
        ),
    ],
    'status bounced' => [
        'status-bounced.json',
        fn (): Envelope => new Envelope(
            version: Envelope::CURRENT_VERSION,
            direction: EventDirection::Status,
            payload: new StatusPayload(
                sendId: '01JD7ZM8X9Y0Z1A2B3C4D5E6F7',
                channel: NotificationChannel::Email,
                status: NotificationStatus::Error,
                type: NotificationType::ToolsPaymentPlanInstallmentDueNotification,
                data: new ToolsPaymentPlanInstallmentDueNotificationData(
                    plan_email_id: '3c4d5e6f-7a8b-4c9d-8e0f-1a2b3c4d5e6f',
                    plan_code: 'PP-2026-0042',
                    position: 2,
                    installment_count: 6,
                    due_on: '2026-12-01',
                    amount: '125.00',
                    beneficiary: 'Trustup SRL',
                    iban: 'BE68539007547034',
                    bic: 'GKCCBEBB',
                    reference: '+++090/9337/55493+++',
                ),
                eventId: '9a510b8f-5c1e-4f4a-8d0e-6f1d2c3b4a5e',
                sendEventId: '2048',
                occurredAt: '2026-11-18T09:16:40Z',
                kind: ChannelEventKind::Bounced,
                failureReason: 'HardBounce',
                providerMessageId: '883953f4-6105-42a2-a16a-77a8eac79483',
                failureDetail: 'The server was unable to deliver your message (ex: unknown user, mailbox not found).',
            ),
        ),
    ],
    'engagement clicked' => [
        'engagement-clicked.json',
        fn (): Envelope => new Envelope(
            version: Envelope::CURRENT_VERSION,
            direction: EventDirection::Engagement,
            payload: new EngagementPayload(
                sendId: '01JD7ZP2Q3R4S5T6V7W8X9Y0Z1',
                channel: NotificationChannel::Email,
                kind: ChannelEventKind::Clicked,
                type: NotificationType::T360OnboardingConfirmationNotification,
                data: new T360OnboardingConfirmationNotificationData(
                    onboarding_email_id: '5e6f7a8b-9c0d-4e1f-8a2b-3c4d5e6f7a8b',
                    subject: 'Votre rendez-vous est confirmé',
                    html_body: '<p>Confirmé</p>',
                    text_body: 'Confirmé',
                ),
                clickedUrl: 'https://onboarding.trustup.pro/r/abc123',
                eventId: '2f4a0b8f-5c1e-4d0e-9a51-6f1d2c3b4a5e',
                sendEventId: '4096',
                occurredAt: '2026-11-18T10:02:11Z',
                providerMessageId: '0a129aee-e1cd-480d-b08d-4f48548ff48d',
            ),
        ),
    ],
]);

function goldenFile(string $name): array
{
    /** @var array<string, mixed> */
    return json_decode((string) file_get_contents(__DIR__.'/../golden/'.$name), true, flags: JSON_THROW_ON_ERROR);
}

it('encodes the payload exactly as its golden file', function (string $file, Closure $envelope): void {
    expect((new EnvelopeSerializer)->encode($envelope()))->toBe(goldenFile($file));
})->with('golden envelopes');

it('decodes its golden file back to the same wire form', function (string $file, Closure $envelope): void {
    $serializer = new EnvelopeSerializer;

    expect($serializer->encode($serializer->decode(goldenFile($file))))->toBe(goldenFile($file));
})->with('golden envelopes');
