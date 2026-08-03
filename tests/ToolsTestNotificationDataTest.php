<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsTestNotificationData;

it('builds ToolsTestNotificationData from valid title and body', function (): void {
    $data = new ToolsTestNotificationData('https://example.test', 'Test Title', 'Test Body');

    expect($data->base_url)->toBe('https://example.test');
    expect($data->title)->toBe('Test Title');
    expect($data->body)->toBe('Test Body');
});

it('round-trips ToolsTestNotificationData via toArray and fromArray', function (): void {
    $original = new ToolsTestNotificationData('https://example.test', 'Hello', 'World');
    $restored = ToolsTestNotificationData::fromArray($original->toArray());

    expect($restored->base_url)->toBe($original->base_url);
    expect($restored->title)->toBe($original->title);
    expect($restored->body)->toBe($original->body);
});
