<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsNewDemandForProfessionalFreemiumNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

it('builds ToolsNewDemandForProfessionalFreemiumNotificationData from its fields', function (): void {
    $data = new ToolsNewDemandForProfessionalFreemiumNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        demand_professional_id: 4242,
        workfield_slug: 'toiture',
        city: 'Liège',
        title: 'Réfection de toiture',
        description: 'Recherche un couvreur pour une réfection complète.',
        temporary_tenant_id: 987,
        claim_token: 'encrypted-claim-token',
    );

    expect($data->base_url)->toBe('https://example.test');
    expect($data->demand_id)->toBe(4321);
    expect($data->demand_professional_id)->toBe(4242);
    expect($data->workfield_slug)->toBe('toiture');
    expect($data->city)->toBe('Liège');
    expect($data->title)->toBe('Réfection de toiture');
    expect($data->description)->toBe('Recherche un couvreur pour une réfection complète.');
    expect($data->temporary_tenant_id)->toBe(987);
    expect($data->claim_token)->toBe('encrypted-claim-token');
});

it('allows city to be null', function (): void {
    $data = new ToolsNewDemandForProfessionalFreemiumNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        demand_professional_id: 4242,
        workfield_slug: 'toiture',
        city: null,
        title: 'Réfection de toiture',
        description: 'Recherche un couvreur pour une réfection complète.',
        temporary_tenant_id: 987,
        claim_token: 'encrypted-claim-token',
    );

    expect($data->city)->toBeNull();
});

it('carries every field in the serialized payload', function (): void {
    $data = new ToolsNewDemandForProfessionalFreemiumNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        demand_professional_id: 4242,
        workfield_slug: 'toiture',
        city: 'Liège',
        title: 'Réfection de toiture',
        description: 'Recherche un couvreur pour une réfection complète.',
        temporary_tenant_id: 987,
        claim_token: 'encrypted-claim-token',
    );

    expect($data->toArray())
        ->toHaveKey('base_url', 'https://example.test')
        ->toHaveKey('demand_id', 4321)
        ->toHaveKey('demand_professional_id', 4242)
        ->toHaveKey('workfield_slug', 'toiture')
        ->toHaveKey('city', 'Liège')
        ->toHaveKey('title', 'Réfection de toiture')
        ->toHaveKey('description', 'Recherche un couvreur pour une réfection complète.')
        ->toHaveKey('temporary_tenant_id', 987)
        ->toHaveKey('claim_token', 'encrypted-claim-token');
});

it('round-trips ToolsNewDemandForProfessionalFreemiumNotificationData via toArray and fromArray', function (): void {
    $original = new ToolsNewDemandForProfessionalFreemiumNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        demand_professional_id: 4242,
        workfield_slug: 'toiture',
        city: 'Liège',
        title: 'Réfection de toiture',
        description: 'Recherche un couvreur pour une réfection complète.',
        temporary_tenant_id: 987,
        claim_token: 'encrypted-claim-token',
    );

    $restored = ToolsNewDemandForProfessionalFreemiumNotificationData::fromArray($original->toArray());

    expect($restored->base_url)->toBe($original->base_url);
    expect($restored->demand_id)->toBe($original->demand_id);
    expect($restored->demand_professional_id)->toBe($original->demand_professional_id);
    expect($restored->workfield_slug)->toBe($original->workfield_slug);
    expect($restored->city)->toBe($original->city);
    expect($restored->title)->toBe($original->title);
    expect($restored->description)->toBe($original->description);
    expect($restored->temporary_tenant_id)->toBe($original->temporary_tenant_id);
    expect($restored->claim_token)->toBe($original->claim_token);
});

it('keeps demand_id, demand_professional_id and temporary_tenant_id integers across a real JSON round-trip', function (): void {
    $original = new ToolsNewDemandForProfessionalFreemiumNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        demand_professional_id: 4242,
        workfield_slug: 'toiture',
        city: 'Liège',
        title: 'Réfection de toiture',
        description: 'Recherche un couvreur pour une réfection complète.',
        temporary_tenant_id: 987,
        claim_token: 'encrypted-claim-token',
    );

    $decoded = json_decode(json_encode($original->toArray()), true);
    $restored = ToolsNewDemandForProfessionalFreemiumNotificationData::fromArray($decoded);

    expect($restored->demand_id)->toBe(4321);
    expect($restored->demand_professional_id)->toBe(4242);
    expect($restored->temporary_tenant_id)->toBe(987);
});

it('reports the tools new-demand-for-professional-freemium notification type', function (): void {
    $data = new ToolsNewDemandForProfessionalFreemiumNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        demand_professional_id: 4242,
        workfield_slug: 'toiture',
        city: 'Liège',
        title: 'Réfection de toiture',
        description: 'Recherche un couvreur pour une réfection complète.',
        temporary_tenant_id: 987,
        claim_token: 'encrypted-claim-token',
    );

    expect($data->notificationType())->toBe(NotificationType::ToolsNewDemandForProfessionalFreemiumNotification);
});
