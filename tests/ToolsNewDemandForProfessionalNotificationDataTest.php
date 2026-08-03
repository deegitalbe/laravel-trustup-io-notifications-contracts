<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsNewDemandForProfessionalNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

it('builds ToolsNewDemandForProfessionalNotificationData from its fields', function (): void {
    $data = new ToolsNewDemandForProfessionalNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        demand_professional_id: 4242,
        workfield_slug: 'toiture',
        city: 'Liège',
        title: 'Réfection de toiture',
        description: 'Recherche un couvreur pour une réfection complète.',
    );

    expect($data->base_url)->toBe('https://example.test');
    expect($data->demand_id)->toBe(4321);
    expect($data->demand_professional_id)->toBe(4242);
    expect($data->workfield_slug)->toBe('toiture');
    expect($data->city)->toBe('Liège');
    expect($data->title)->toBe('Réfection de toiture');
    expect($data->description)->toBe('Recherche un couvreur pour une réfection complète.');
});

it('allows city to be null', function (): void {
    $data = new ToolsNewDemandForProfessionalNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        demand_professional_id: 4242,
        workfield_slug: 'toiture',
        city: null,
        title: 'Réfection de toiture',
        description: 'Recherche un couvreur pour une réfection complète.',
    );

    expect($data->city)->toBeNull();
});

it('carries every field in the serialized payload', function (): void {
    $data = new ToolsNewDemandForProfessionalNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        demand_professional_id: 4242,
        workfield_slug: 'toiture',
        city: 'Liège',
        title: 'Réfection de toiture',
        description: 'Recherche un couvreur pour une réfection complète.',
    );

    expect($data->toArray())
        ->toHaveKey('base_url', 'https://example.test')
        ->toHaveKey('demand_id', 4321)
        ->toHaveKey('demand_professional_id', 4242)
        ->toHaveKey('workfield_slug', 'toiture')
        ->toHaveKey('city', 'Liège')
        ->toHaveKey('title', 'Réfection de toiture')
        ->toHaveKey('description', 'Recherche un couvreur pour une réfection complète.');
});

it('round-trips ToolsNewDemandForProfessionalNotificationData via toArray and fromArray', function (): void {
    $original = new ToolsNewDemandForProfessionalNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        demand_professional_id: 4242,
        workfield_slug: 'toiture',
        city: 'Liège',
        title: 'Réfection de toiture',
        description: 'Recherche un couvreur pour une réfection complète.',
    );

    $restored = ToolsNewDemandForProfessionalNotificationData::fromArray($original->toArray());

    expect($restored->base_url)->toBe($original->base_url);
    expect($restored->demand_id)->toBe($original->demand_id);
    expect($restored->demand_professional_id)->toBe($original->demand_professional_id);
    expect($restored->workfield_slug)->toBe($original->workfield_slug);
    expect($restored->city)->toBe($original->city);
    expect($restored->title)->toBe($original->title);
    expect($restored->description)->toBe($original->description);
});

it('keeps demand_id and demand_professional_id integers across a real JSON round-trip', function (): void {
    $original = new ToolsNewDemandForProfessionalNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        demand_professional_id: 4242,
        workfield_slug: 'toiture',
        city: 'Liège',
        title: 'Réfection de toiture',
        description: 'Recherche un couvreur pour une réfection complète.',
    );

    $decoded = json_decode(json_encode($original->toArray()), true);
    $restored = ToolsNewDemandForProfessionalNotificationData::fromArray($decoded);

    expect($restored->demand_id)->toBe(4321);
    expect($restored->demand_professional_id)->toBe(4242);
});

it('defaults city to null when missing from the payload', function (): void {
    $restored = ToolsNewDemandForProfessionalNotificationData::fromArray([
        'base_url' => 'https://example.test',
        'demand_id' => 4321,
        'demand_professional_id' => 4242,
        'workfield_slug' => 'toiture',
        'title' => 'Réfection de toiture',
        'description' => 'Recherche un couvreur pour une réfection complète.',
    ]);

    expect($restored->city)->toBeNull();
});

it('reports the tools new-demand-for-professional notification type', function (): void {
    $data = new ToolsNewDemandForProfessionalNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        demand_professional_id: 4242,
        workfield_slug: 'toiture',
        city: 'Liège',
        title: 'Réfection de toiture',
        description: 'Recherche un couvreur pour une réfection complète.',
    );

    expect($data->notificationType())->toBe(NotificationType::ToolsNewDemandForProfessionalNotification);
});
