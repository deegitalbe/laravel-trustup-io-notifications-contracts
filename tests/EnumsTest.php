<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Enums\EventDirection;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationChannel;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationStatus;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\SenderRole;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\Source;

it('Source enum has Tools and Marketplace cases with correct values', function (): void {
    expect(Source::Tools->value)->toBe('tools');
    expect(Source::Marketplace->value)->toBe('marketplace');
});

it('Source enum has the t360 case', function (): void {
    expect(Source::T360->value)->toBe('t360');
});

it('SenderRole enum has the onboarding case', function (): void {
    expect(SenderRole::Onboarding->value)->toBe('onboarding');
});

it('NotificationChannel enum has Email, Sms and Push cases', function (NotificationChannel $channel): void {
    expect($channel->value)->toBeIn(['email', 'sms', 'push']);
})->with(NotificationChannel::cases());

it('Source::from throws ValueError for unknown source value', function (): void {
    expect(fn () => Source::from('unknown'))->toThrow(ValueError::class);
});

it('NotificationChannel::from throws ValueError for unknown channel value', function (): void {
    expect(fn () => NotificationChannel::from('unknown'))->toThrow(ValueError::class);
});

it('NotificationStatus enum has Pending, Sent, Delivered and Error cases', function (): void {
    expect(NotificationStatus::Pending->value)->toBe('pending');
    expect(NotificationStatus::Sent->value)->toBe('sent');
    expect(NotificationStatus::Delivered->value)->toBe('delivered');
    expect(NotificationStatus::Error->value)->toBe('error');
});

it('EventDirection enum has Request and Status cases', function (): void {
    expect(EventDirection::Request->value)->toBe('request');
    expect(EventDirection::Status->value)->toBe('status');
});
