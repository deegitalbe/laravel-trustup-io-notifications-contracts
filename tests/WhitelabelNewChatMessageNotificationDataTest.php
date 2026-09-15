<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\WhitelabelNewChatMessageNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidNotificationDataException;

it('builds WhitelabelNewChatMessageNotificationData from its fields', function (): void {
    $data = new WhitelabelNewChatMessageNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        claim_token: 'tok_9f3ba71c',
        cometchat_group_guid: 'cg_1a2b3c',
        locale: 'fr',
    );

    expect($data->base_url)->toBe('https://example.test');
    expect($data->demand_id)->toBe(4321);
    expect($data->claim_token)->toBe('tok_9f3ba71c');
    expect($data->cometchat_group_guid)->toBe('cg_1a2b3c');
    expect($data->locale)->toBe('fr');
});

it('allows claim_token to be null by default', function (): void {
    $data = new WhitelabelNewChatMessageNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        locale: 'fr',
    );

    expect($data->claim_token)->toBeNull();
});

it('allows cometchat_group_guid to be null by default', function (): void {
    $data = new WhitelabelNewChatMessageNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        locale: 'fr',
    );

    expect($data->cometchat_group_guid)->toBeNull();
});

it('carries every field in the serialized payload', function (): void {
    $data = new WhitelabelNewChatMessageNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        claim_token: 'tok_9f3ba71c',
        cometchat_group_guid: 'cg_1a2b3c',
        locale: 'fr',
    );

    expect($data->toArray())
        ->toHaveKey('base_url', 'https://example.test')
        ->toHaveKey('demand_id', 4321)
        ->toHaveKey('claim_token', 'tok_9f3ba71c')
        ->toHaveKey('cometchat_group_guid', 'cg_1a2b3c')
        ->toHaveKey('locale', 'fr');
});

it('round-trips WhitelabelNewChatMessageNotificationData via toArray and fromArray', function (): void {
    $original = new WhitelabelNewChatMessageNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        claim_token: 'tok_9f3ba71c',
        cometchat_group_guid: 'cg_1a2b3c',
        locale: 'fr',
    );

    $restored = WhitelabelNewChatMessageNotificationData::fromArray($original->toArray());

    expect($restored->base_url)->toBe($original->base_url);
    expect($restored->demand_id)->toBe($original->demand_id);
    expect($restored->claim_token)->toBe($original->claim_token);
    expect($restored->cometchat_group_guid)->toBe($original->cometchat_group_guid);
    expect($restored->locale)->toBe($original->locale);
});

it('keeps demand_id an integer across a real JSON round-trip', function (): void {
    $original = new WhitelabelNewChatMessageNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        claim_token: 'tok_9f3ba71c',
        locale: 'fr',
    );

    $decoded = json_decode(json_encode($original->toArray()), true);
    $restored = WhitelabelNewChatMessageNotificationData::fromArray($decoded);

    expect($restored->demand_id)->toBe(4321);
});

it('rejects a demand_id that arrives as a numeric string rather than coercing it', function (): void {
    expect(fn () => WhitelabelNewChatMessageNotificationData::fromArray([
        'base_url' => 'https://example.test',
        'demand_id' => '4321',
        'claim_token' => 'tok_9f3ba71c',
        'locale' => 'fr',
    ]))->toThrow(TypeError::class);
});

it('defaults claim_token to null when missing from the payload', function (): void {
    $restored = WhitelabelNewChatMessageNotificationData::fromArray([
        'base_url' => 'https://example.test',
        'demand_id' => 4321,
        'locale' => 'fr',
    ]);

    expect($restored->claim_token)->toBeNull();
});

it('defaults cometchat_group_guid to null when missing from the payload', function (): void {
    $restored = WhitelabelNewChatMessageNotificationData::fromArray([
        'base_url' => 'https://example.test',
        'demand_id' => 4321,
        'locale' => 'fr',
    ]);

    expect($restored->cometchat_group_guid)->toBeNull();
});

it('rejects a payload missing the required locale key', function (): void {
    expect(fn () => WhitelabelNewChatMessageNotificationData::fromArray([
        'base_url' => 'https://example.test',
        'demand_id' => 4321,
        'claim_token' => 'tok_9f3ba71c',
    ]))->toThrow(InvalidNotificationDataException::class);
});

it('reports the whitelabel new-chat-message-for-customer notification type', function (): void {
    $data = new WhitelabelNewChatMessageNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        claim_token: 'tok_9f3ba71c',
        locale: 'fr',
    );

    expect($data->notificationType())->toBe(NotificationType::WhitelabelNewChatMessageNotification);
});

it('allows pro_name, pro_phone, pro_email and pro_logo to be null by default', function (): void {
    $data = new WhitelabelNewChatMessageNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        locale: 'fr',
    );

    expect($data->pro_name)->toBeNull();
    expect($data->pro_phone)->toBeNull();
    expect($data->pro_email)->toBeNull();
    expect($data->pro_logo)->toBeNull();
});

it('carries pro_name, pro_phone, pro_email and pro_logo in the serialized payload', function (): void {
    $data = new WhitelabelNewChatMessageNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        locale: 'fr',
        pro_name: 'Toiture Martin',
        pro_phone: '+32 470 00 00 00',
        pro_email: 'contact@toituremartin.be',
        pro_logo: 'https://shared-assets.trustup.io/notifications/pro-logo.png',
    );

    expect($data->toArray())
        ->toHaveKey('pro_name', 'Toiture Martin')
        ->toHaveKey('pro_phone', '+32 470 00 00 00')
        ->toHaveKey('pro_email', 'contact@toituremartin.be')
        ->toHaveKey('pro_logo', 'https://shared-assets.trustup.io/notifications/pro-logo.png');
});

it('derives has_pro_logo as true in the email variables when pro_logo is set', function (): void {
    $data = new WhitelabelNewChatMessageNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        locale: 'fr',
        pro_logo: 'https://shared-assets.trustup.io/notifications/pro-logo.png',
    );

    expect($data->toEmail()->variables)->toHaveKey('has_pro_logo', true);
});

it('derives has_pro_logo as false in the email variables when pro_logo is null', function (): void {
    $data = new WhitelabelNewChatMessageNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        locale: 'fr',
    );

    expect($data->toEmail()->variables)->toHaveKey('has_pro_logo', false);
});

it('does not include has_pro_logo in the plain serialized payload', function (): void {
    $data = new WhitelabelNewChatMessageNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        locale: 'fr',
        pro_logo: 'https://shared-assets.trustup.io/notifications/pro-logo.png',
    );

    expect($data->toArray())->not->toHaveKey('has_pro_logo');
});

it('uses pro_name as the email sender name when set', function (): void {
    $data = new WhitelabelNewChatMessageNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        locale: 'fr',
        pro_name: 'Toiture Martin',
    );

    expect($data->toEmail()->senderName)->toBe('Toiture Martin');
});

it('has a null email sender name when pro_name is null', function (): void {
    $data = new WhitelabelNewChatMessageNotificationData(
        base_url: 'https://example.test',
        demand_id: 4321,
        locale: 'fr',
    );

    expect($data->toEmail()->senderName)->toBeNull();
});
