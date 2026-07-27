<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

it('every NotificationType data class returns its own notificationType without exception', function (NotificationType $type): void {
    $dataClass = $type->dataClass();
    $minimalArgs = match ($type) {
        NotificationType::ToolsTestNotification => ['Test Title', 'Test Body'],
        NotificationType::ToolsFullLocaleTestNotification => ['Test Title', 'Test Body'],
        NotificationType::ToolsCommentNotification => [
            'https://example.com/product',
            'Product Name',
            'Comment body',
            [],
            'Commenter',
            '2026-07-23 12:00',
            'https://example.com/action',
            'https://example.com/notifications',
            'Company',
            'Company Address',
        ],
        default => throw new LogicException("No minimal args defined for [{$type->value}] in coherence test."),
    };

    $instance = new $dataClass(...$minimalArgs);

    expect($instance->notificationType())->toBe($type);
})->with(NotificationType::cases());
