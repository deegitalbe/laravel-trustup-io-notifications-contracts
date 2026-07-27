<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsTestNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

it('NotificationType::ToolsTestNotification returns FQCN of ToolsTestNotificationData', function (): void {
    expect(NotificationType::ToolsTestNotification->dataClass())
        ->toBe(ToolsTestNotificationData::class);
});

it('every NotificationType case has a dataClass without exception', function (NotificationType $type): void {
    expect($type->dataClass())->toBeString()->not->toBeEmpty();
})->with(NotificationType::cases());
