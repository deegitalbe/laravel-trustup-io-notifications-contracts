<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Enums\ChannelEventKind;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationStatus;

it('maps each kind to its canonical status', function (ChannelEventKind $kind, NotificationStatus $expected): void {
    expect($kind->canonicalStatus())->toBe($expected);
})->with([
    [ChannelEventKind::Delivered, NotificationStatus::Delivered],
    [ChannelEventKind::Bounced, NotificationStatus::Error],
    [ChannelEventKind::SpamComplaint, NotificationStatus::Error],
    [ChannelEventKind::Rejected, NotificationStatus::Error],
    [ChannelEventKind::Undeliverable, NotificationStatus::Error],
    [ChannelEventKind::Opened, NotificationStatus::Sent],
    [ChannelEventKind::Clicked, NotificationStatus::Sent],
]);

it('flags only opened and clicked as engagement', function (ChannelEventKind $kind, bool $expected): void {
    expect($kind->isEngagement())->toBe($expected);
})->with([
    [ChannelEventKind::Opened, true],
    [ChannelEventKind::Clicked, true],
    [ChannelEventKind::Delivered, false],
    [ChannelEventKind::Bounced, false],
    [ChannelEventKind::SpamComplaint, false],
    [ChannelEventKind::Rejected, false],
    [ChannelEventKind::Undeliverable, false],
]);
