<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsNewDemandNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

it('builds ToolsNewDemandNotificationData from its fields', function (): void {
    $data = new ToolsNewDemandNotificationData(
        first_name: 'Jean',
        workfield_name: 'Toiture',
        demand_professional_id: 4242,
    );

    expect($data->first_name)->toBe('Jean');
    expect($data->workfield_name)->toBe('Toiture');
    expect($data->demand_professional_id)->toBe(4242);
});

it('carries every field in the serialized payload', function (): void {
    $data = new ToolsNewDemandNotificationData(
        first_name: 'Jean',
        workfield_name: 'Toiture',
        demand_professional_id: 4242,
    );

    expect($data->toArray())
        ->toHaveKey('first_name', 'Jean')
        ->toHaveKey('workfield_name', 'Toiture')
        ->toHaveKey('demand_professional_id', 4242);
});

it('round-trips ToolsNewDemandNotificationData via toArray and fromArray', function (): void {
    $original = new ToolsNewDemandNotificationData(
        first_name: 'Jean',
        workfield_name: 'Toiture',
        demand_professional_id: 4242,
    );

    $restored = ToolsNewDemandNotificationData::fromArray($original->toArray());

    expect($restored->first_name)->toBe($original->first_name);
    expect($restored->workfield_name)->toBe($original->workfield_name);
    expect($restored->demand_professional_id)->toBe($original->demand_professional_id);
});

it('reports the tools new-demand notification type', function (): void {
    $data = new ToolsNewDemandNotificationData(
        first_name: 'Jean',
        workfield_name: 'Toiture',
        demand_professional_id: 4242,
    );

    expect($data->notificationType())->toBe(NotificationType::ToolsNewDemandNotification);
});
