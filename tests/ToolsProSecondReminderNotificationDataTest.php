<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsProSecondReminderNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

it('builds ToolsProSecondReminderNotificationData from its fields', function (): void {
    $data = new ToolsProSecondReminderNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        demand_professional_id: 987,
        title: 'Fuite d\'eau urgente',
        workfield_slug: 'plomberie',
        city: 'Bruxelles',
    );

    expect($data->base_url)->toBe('https://example.test');
    expect($data->demand_id)->toBe(4321);
    expect($data->demand_professional_id)->toBe(987);
    expect($data->title)->toBe('Fuite d\'eau urgente');
    expect($data->workfield_slug)->toBe('plomberie');
    expect($data->city)->toBe('Bruxelles');
});

it('defaults workfield_slug and city to null when omitted', function (): void {
    $data = new ToolsProSecondReminderNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        demand_professional_id: 987,
        title: 'Fuite d\'eau urgente',
    );

    expect($data->workfield_slug)->toBeNull();
    expect($data->city)->toBeNull();
});

it('carries every field in the serialized payload', function (): void {
    $data = new ToolsProSecondReminderNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        demand_professional_id: 987,
        title: 'Fuite d\'eau urgente',
        workfield_slug: 'plomberie',
        city: 'Bruxelles',
    );

    expect($data->toArray())
        ->toHaveKey('base_url', 'https://example.test')
        ->toHaveKey('demand_id', 4321)
        ->toHaveKey('demand_professional_id', 987)
        ->toHaveKey('title', 'Fuite d\'eau urgente')
        ->toHaveKey('workfield_slug', 'plomberie')
        ->toHaveKey('city', 'Bruxelles');
});

it('allows workfield_slug and city to be explicitly null', function (): void {
    $data = new ToolsProSecondReminderNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        demand_professional_id: 987,
        title: 'Fuite d\'eau urgente',
        workfield_slug: null,
        city: null,
    );

    expect($data->workfield_slug)->toBeNull();
    expect($data->city)->toBeNull();
});

it('round-trips ToolsProSecondReminderNotificationData via toArray and fromArray', function (): void {
    $original = new ToolsProSecondReminderNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        demand_professional_id: 987,
        title: 'Fuite d\'eau urgente',
        workfield_slug: 'plomberie',
        city: 'Bruxelles',
    );

    $restored = ToolsProSecondReminderNotificationData::fromArray($original->toArray());

    expect($restored->base_url)->toBe($original->base_url);
    expect($restored->demand_id)->toBe($original->demand_id);
    expect($restored->demand_professional_id)->toBe($original->demand_professional_id);
    expect($restored->title)->toBe($original->title);
    expect($restored->workfield_slug)->toBe($original->workfield_slug);
    expect($restored->city)->toBe($original->city);
});

it('keeps demand_id and demand_professional_id integers across a real JSON round-trip', function (): void {
    $original = new ToolsProSecondReminderNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        demand_professional_id: 987,
        title: 'Fuite d\'eau urgente',
        workfield_slug: 'plomberie',
        city: 'Bruxelles',
    );

    $decoded = json_decode(json_encode($original->toArray()), true);
    $restored = ToolsProSecondReminderNotificationData::fromArray($decoded);

    expect($restored->demand_id)->toBe(4321);
    expect($restored->demand_professional_id)->toBe(987);
});

it('rejects a demand_id that arrives as a numeric string rather than coercing it', function (): void {
    expect(fn () => ToolsProSecondReminderNotificationData::fromArray([
        'base_url' => 'https://example.test',
        'demand_id' => '4321',
        'demand_professional_id' => 987,
        'title' => 'Fuite d\'eau urgente',
        'workfield_slug' => 'plomberie',
        'city' => 'Bruxelles',
    ]))->toThrow(TypeError::class);
});

it('reports the tools pro-second-reminder notification type', function (): void {
    $data = new ToolsProSecondReminderNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        demand_professional_id: 987,
        title: 'Fuite d\'eau urgente',
        workfield_slug: 'plomberie',
        city: 'Bruxelles',
    );

    expect($data->notificationType())->toBe(NotificationType::ToolsProSecondReminderNotification);
});
