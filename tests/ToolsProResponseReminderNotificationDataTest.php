<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsProResponseReminderNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

it('builds ToolsProResponseReminderNotificationData from its fields', function (): void {
    $data = new ToolsProResponseReminderNotificationData(
        demand_id: 4321,
        demand_professional_id: 987,
        workfield_slug: 'plomberie',
        title: 'Fuite d\'eau urgente',
    );

    expect($data->demand_id)->toBe(4321);
    expect($data->demand_professional_id)->toBe(987);
    expect($data->workfield_slug)->toBe('plomberie');
    expect($data->title)->toBe('Fuite d\'eau urgente');
});

it('defaults workfield_slug to null when omitted', function (): void {
    $data = new ToolsProResponseReminderNotificationData(
        demand_id: 4321,
        demand_professional_id: 987,
        title: 'Fuite d\'eau urgente',
    );

    expect($data->workfield_slug)->toBeNull();
});

it('allows workfield_slug to be explicitly null', function (): void {
    $data = new ToolsProResponseReminderNotificationData(
        demand_id: 4321,
        demand_professional_id: 987,
        workfield_slug: null,
        title: 'Fuite d\'eau urgente',
    );

    expect($data->workfield_slug)->toBeNull();
});

it('carries every field in the serialized payload', function (): void {
    $data = new ToolsProResponseReminderNotificationData(
        demand_id: 4321,
        demand_professional_id: 987,
        workfield_slug: 'plomberie',
        title: 'Fuite d\'eau urgente',
    );

    expect($data->toArray())
        ->toHaveKey('demand_id', 4321)
        ->toHaveKey('demand_professional_id', 987)
        ->toHaveKey('workfield_slug', 'plomberie')
        ->toHaveKey('title', 'Fuite d\'eau urgente');
});

it('round-trips ToolsProResponseReminderNotificationData via toArray and fromArray', function (): void {
    $original = new ToolsProResponseReminderNotificationData(
        demand_id: 4321,
        demand_professional_id: 987,
        workfield_slug: 'plomberie',
        title: 'Fuite d\'eau urgente',
    );

    $restored = ToolsProResponseReminderNotificationData::fromArray($original->toArray());

    expect($restored->demand_id)->toBe($original->demand_id);
    expect($restored->demand_professional_id)->toBe($original->demand_professional_id);
    expect($restored->workfield_slug)->toBe($original->workfield_slug);
    expect($restored->title)->toBe($original->title);
});

it('keeps demand_id and demand_professional_id integers across a real JSON round-trip', function (): void {
    $original = new ToolsProResponseReminderNotificationData(
        demand_id: 4321,
        demand_professional_id: 987,
        workfield_slug: 'plomberie',
        title: 'Fuite d\'eau urgente',
    );

    $decoded = json_decode(json_encode($original->toArray()), true);
    $restored = ToolsProResponseReminderNotificationData::fromArray($decoded);

    expect($restored->demand_id)->toBe(4321);
    expect($restored->demand_professional_id)->toBe(987);
});

it('rejects a demand_id that arrives as a numeric string rather than coercing it', function (): void {
    expect(fn () => ToolsProResponseReminderNotificationData::fromArray([
        'demand_id' => '4321',
        'demand_professional_id' => 987,
        'workfield_slug' => 'plomberie',
        'title' => 'Fuite d\'eau urgente',
    ]))->toThrow(TypeError::class);
});

it('reports the tools pro-response-reminder notification type', function (): void {
    $data = new ToolsProResponseReminderNotificationData(
        demand_id: 4321,
        demand_professional_id: 987,
        workfield_slug: 'plomberie',
        title: 'Fuite d\'eau urgente',
    );

    expect($data->notificationType())->toBe(NotificationType::ToolsProResponseReminderNotification);
});
