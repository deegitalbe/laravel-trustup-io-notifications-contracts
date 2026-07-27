<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Enums\ChannelEventKind;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationChannel;
use Deegitalbe\TrustupIoNotificationsContracts\Events\EmailChannelEvent;
use Deegitalbe\TrustupIoNotificationsContracts\Events\SmsChannelEvent;

it('round-trips an EmailChannelEvent through toArray and fromArray, exposing accessors', function (): void {
    $event = EmailChannelEvent::fromArray([
        'send_id' => 'send-1',
        'channel' => 'email',
        'kind' => 'bounced',
        'occurred_at' => '2026-06-24T10:00:00Z',
        'raw_provider_code' => 'HardBounce',
        'clicked_url' => null,
        'bounce_type' => 'HardBounce',
    ]);

    expect($event->sendId())->toBe('send-1')
        ->and($event->channel())->toBe(NotificationChannel::Email)
        ->and($event->kind())->toBe(ChannelEventKind::Bounced)
        ->and($event->occurredAt())->toBe('2026-06-24T10:00:00Z')
        ->and($event->rawProviderCode())->toBe('HardBounce')
        ->and($event->bounceType)->toBe('HardBounce')
        ->and($event->toArray())->toBe([
            'send_id' => 'send-1',
            'channel' => 'email',
            'kind' => 'bounced',
            'occurred_at' => '2026-06-24T10:00:00Z',
            'raw_provider_code' => 'HardBounce',
            'clicked_url' => null,
            'bounce_type' => 'HardBounce',
        ]);
});

it('round-trips an SmsChannelEvent through toArray and fromArray, exposing accessors', function (): void {
    $event = SmsChannelEvent::fromArray([
        'send_id' => 'send-2',
        'channel' => 'sms',
        'kind' => 'rejected',
        'occurred_at' => '2026-06-24T11:00:00Z',
        'raw_provider_code' => '6',
        'error_code' => '6',
        'error_detail' => 'Invalid number',
    ]);

    expect($event->sendId())->toBe('send-2')
        ->and($event->channel())->toBe(NotificationChannel::Sms)
        ->and($event->kind())->toBe(ChannelEventKind::Rejected)
        ->and($event->occurredAt())->toBe('2026-06-24T11:00:00Z')
        ->and($event->rawProviderCode())->toBe('6')
        ->and($event->errorCode)->toBe('6')
        ->and($event->errorDetail)->toBe('Invalid number')
        ->and($event->toArray())->toBe([
            'send_id' => 'send-2',
            'channel' => 'sms',
            'kind' => 'rejected',
            'occurred_at' => '2026-06-24T11:00:00Z',
            'raw_provider_code' => '6',
            'error_code' => '6',
            'error_detail' => 'Invalid number',
        ]);
});

it('defaults nullable fields to null when absent from the payload', function (): void {
    $event = EmailChannelEvent::fromArray([
        'send_id' => 'send-3',
        'channel' => 'email',
        'kind' => 'delivered',
        'occurred_at' => '2026-06-24T12:00:00Z',
    ]);

    expect($event->rawProviderCode())->toBeNull()
        ->and($event->clickedUrl)->toBeNull()
        ->and($event->bounceType)->toBeNull();
});
