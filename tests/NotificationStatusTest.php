<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationStatus;

it('ranks statuses so error is highest and pending lowest', function (): void {
    expect(NotificationStatus::Pending->rank())->toBe(0)
        ->and(NotificationStatus::Sent->rank())->toBe(1)
        ->and(NotificationStatus::Delivered->rank())->toBe(2)
        ->and(NotificationStatus::Error->rank())->toBe(PHP_INT_MAX);
});

it('flags only error as an error status', function (NotificationStatus $status, bool $expected): void {
    expect($status->isError())->toBe($expected);
})->with([
    [NotificationStatus::Error, true],
    [NotificationStatus::Pending, false],
    [NotificationStatus::Sent, false],
    [NotificationStatus::Delivered, false],
]);
