<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsTestNotificationData;

it('builds ToolsTestNotificationData from valid title and body', function (): void {
    $data = new ToolsTestNotificationData('Test Title', 'Test Body');

    expect($data->title)->toBe('Test Title');
    expect($data->body)->toBe('Test Body');
});

it('round-trips ToolsTestNotificationData via toArray and fromArray', function (): void {
    $original = new ToolsTestNotificationData('Hello', 'World');
    $restored = ToolsTestNotificationData::fromArray($original->toArray());

    expect($restored->title)->toBe($original->title);
    expect($restored->body)->toBe($original->body);
});
