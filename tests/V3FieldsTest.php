<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceAssignationActivationNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceDemandReceivedNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceNewChatMessageForCustomerNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceUnclaimedDemandReminderNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceUserAssignmentNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\MarketplaceUserReassignNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsNewChatMessageForProfessionalNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsNewDemandForProfessionalFreemiumNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsNewDemandForProfessionalNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsProResponseReminderNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsProSecondReminderNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\WhitelabelDemandReceivedNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Data\WhitelabelNewChatMessageNotificationData;

const V3_PROS = [
    ['name' => 'Dupont SRL', 'logo' => 'https://example.test/logo.png', 'trust_score' => '9.4', 'trust_score_label' => 'excellent', 'conversation_url' => 'https://example.test/c/1', 'select_url' => 'https://example.test/s/1', 'call_url' => 'tel:+32470000000'],
];

const V3_RATING_LINKS = [
    ['note' => 1, 'url' => 'https://example.test/feedback?note=1'],
    ['note' => 5, 'url' => 'https://example.test/feedback?note=5'],
];

const V3_DEMAND = [
    'demand_description' => 'Toiture de 80 m2',
    'demand_cancel_url' => 'https://example.test/cancel',
    'demand_illustration_url' => 'https://example.test/photo.jpg',
];

const V3_PRO_LINKS = [
    'interested_url' => 'https://example.test/interested',
    'declined_url' => 'https://example.test/declined',
];

dataset('v3 data classes', [
    'ToolsNewDemandForProfessional' => [
        ToolsNewDemandForProfessionalNotificationData::class,
        ['base_url' => 'https://example.test', 'demand_id' => 1, 'demand_professional_id' => 2, 'workfield_slug' => 'toiture', 'city' => null, 'title' => 'T', 'description' => 'D'],
        ['workfield_label' => 'Toiture', 'first_name' => 'Camille', 'demand_type' => 'direct', 'is_direct' => true, 'demand_source' => 'pro_website', 'is_pro_website' => true, 'demand_illustration_url' => 'https://example.test/photo.jpg'] + V3_PRO_LINKS,
    ],
    'ToolsNewDemandForProfessionalFreemium' => [
        ToolsNewDemandForProfessionalFreemiumNotificationData::class,
        ['base_url' => 'https://example.test', 'demand_id' => 1, 'demand_professional_id' => 2, 'workfield_slug' => 'toiture', 'city' => null, 'title' => 'T', 'description' => 'D', 'temporary_tenant_id' => 3, 'claim_token' => 'tok'],
        ['demand_illustration_url' => 'https://example.test/photo.jpg'] + V3_PRO_LINKS,
    ],
    'ToolsProResponseReminder' => [
        ToolsProResponseReminderNotificationData::class,
        ['base_url' => 'https://example.test', 'demand_id' => 1, 'demand_professional_id' => 2, 'title' => 'T'],
        ['city' => 'Namur', 'demand_type' => 'global', 'is_direct' => false, 'demand_illustration_url' => 'https://example.test/photo.jpg'] + V3_PRO_LINKS,
    ],
    'ToolsProSecondReminder' => [
        ToolsProSecondReminderNotificationData::class,
        ['base_url' => 'https://example.test', 'demand_id' => 1, 'demand_professional_id' => 2, 'title' => 'T'],
        ['demand_type' => 'direct', 'is_direct' => true, 'demand_illustration_url' => 'https://example.test/photo.jpg'] + V3_PRO_LINKS,
    ],
    'ToolsNewChatMessageForProfessional' => [
        ToolsNewChatMessageForProfessionalNotificationData::class,
        ['base_url' => 'https://example.test', 'demand_id' => 1, 'demand_professional_id' => 2],
        ['first_name' => 'Camille', 'workfield_label' => 'Toiture', 'city' => 'Namur'],
    ],
    'MarketplaceDemandReceived' => [
        MarketplaceDemandReceivedNotificationData::class,
        ['base_url' => 'https://example.test', 'demand_id' => 1],
        ['email_already_known' => true, 'demand_type' => 'direct', 'is_direct' => true, 'pro_name' => 'Dupont SRL'] + V3_DEMAND,
    ],
    'MarketplaceUserAssignment' => [
        MarketplaceUserAssignmentNotificationData::class,
        ['base_url' => 'https://example.test', 'demand_id' => 1, 'professional_count' => 3],
        ['pros' => V3_PROS] + V3_DEMAND,
    ],
    'MarketplaceNewChatMessageForCustomer' => [
        MarketplaceNewChatMessageForCustomerNotificationData::class,
        ['base_url' => 'https://example.test', 'demand_id' => 1, 'locale' => 'fr'],
        ['pro_name' => 'Dupont SRL', 'pro_logo' => 'https://example.test/logo.png', 'pro_trust_score' => '9.4', 'pro_trust_score_label' => 'excellent', 'workfield_label' => 'Toiture', 'city' => 'Namur', 'rating_links' => V3_RATING_LINKS] + V3_DEMAND,
    ],
    'MarketplaceUnclaimedDemandReminder' => [
        MarketplaceUnclaimedDemandReminderNotificationData::class,
        ['base_url' => 'https://example.test', 'demand_id' => 1, 'title' => 'T', 'workfield_label' => 'Toiture'],
        ['demand_type' => 'direct', 'is_direct' => true, 'demand_source' => 'pro_website', 'is_pro_website' => true, 'pro_name' => 'Dupont SRL', 'pro_logo' => 'https://example.test/logo.png', 'pro_phone' => '+32470000000', 'pro_email' => 'pro@example.test'] + V3_DEMAND,
    ],
    'MarketplaceUserReassign' => [
        MarketplaceUserReassignNotificationData::class,
        ['base_url' => 'https://example.test', 'demand_id' => 1],
        ['workfield_label' => 'Toiture', 'city' => 'Namur', 'demand_type' => 'direct', 'is_direct' => true, 'pro_name' => 'Dupont SRL', 'conversation_url' => 'https://example.test/c/1'] + V3_DEMAND,
    ],
    'MarketplaceAssignationActivation' => [
        MarketplaceAssignationActivationNotificationData::class,
        ['base_url' => 'https://example.test', 'demand_id' => 1],
        ['pros' => V3_PROS, 'workfield_label' => 'Toiture', 'city' => 'Namur', 'demand_type' => 'direct', 'is_direct' => true, 'demand_source' => 'pro_website', 'is_pro_website' => true, 'pro_name' => 'Dupont SRL', 'pro_logo' => 'https://example.test/logo.png', 'pro_phone' => '+32470000000', 'pro_email' => 'pro@example.test'] + V3_DEMAND,
    ],
    'WhitelabelDemandReceived' => [
        WhitelabelDemandReceivedNotificationData::class,
        ['base_url' => 'https://example.test', 'demand_id' => 1],
        ['workfield_label' => 'Toiture', 'city' => 'Namur'],
    ],
    'WhitelabelNewChatMessage' => [
        WhitelabelNewChatMessageNotificationData::class,
        ['base_url' => 'https://example.test', 'demand_id' => 1, 'locale' => 'fr'],
        ['workfield_label' => 'Toiture', 'city' => 'Namur'],
    ],
]);

it('builds from a legacy payload without the V3 keys and defaults them to null', function (string $class, array $legacy, array $v3): void {
    $data = $class::fromArray($legacy);

    foreach (array_keys($v3) as $key) {
        expect($data->{$key})->toBeNull();
    }
})->with('v3 data classes');

it('keeps the legacy keys intact when the V3 keys are absent', function (string $class, array $legacy, array $v3): void {
    $payload = $class::fromArray($legacy)->toArray();

    foreach ($legacy as $key => $value) {
        expect($payload)->toHaveKey($key, $value);
    }
})->with('v3 data classes');

it('reads every V3 key when present', function (string $class, array $legacy, array $v3): void {
    $data = $class::fromArray($legacy + $v3);

    foreach ($v3 as $key => $value) {
        expect($data->{$key})->toBe($value);
    }
})->with('v3 data classes');

it('round-trips the V3 keys through a real JSON encode and decode', function (string $class, array $legacy, array $v3): void {
    $original = $class::fromArray($legacy + $v3);

    $restored = $class::fromArray(json_decode(json_encode($original->toArray()), true));

    expect($restored->toArray())->toBe($original->toArray());
})->with('v3 data classes');

it('targets the whitelabel Postmark alias only when the demand comes from the pro website', function (string $class, array $legacy, string $default, string $whitelabel): void {
    expect($class::fromArray($legacy)->emailTemplate())->toBe($default);
    expect($class::fromArray($legacy + ['is_pro_website' => false])->emailTemplate())->toBe($default);
    expect($class::fromArray($legacy + ['is_pro_website' => true])->emailTemplate())->toBe($whitelabel);
})->with([
    'unclaimed demand reminder' => [
        MarketplaceUnclaimedDemandReminderNotificationData::class,
        ['base_url' => 'https://example.test', 'demand_id' => 1, 'title' => 'T', 'workfield_label' => 'Toiture'],
        'marketplace-unclaimed-demand-reminder-notification',
        'marketplace-whitelabel-unclaimed-demand-reminder-notification',
    ],
    'assignation activation' => [
        MarketplaceAssignationActivationNotificationData::class,
        ['base_url' => 'https://example.test', 'demand_id' => 1],
        'marketplace-assignation-activation-notification',
        'marketplace-whitelabel-assignation-activation-notification',
    ],
]);

it('derives has_pro_logo in the email variables from a non empty pro_logo', function (string $class, array $legacy): void {
    $with = $class::fromArray($legacy + ['pro_logo' => 'https://example.test/logo.png']);
    $empty = $class::fromArray($legacy + ['pro_logo' => '']);
    $without = $class::fromArray($legacy);

    expect($with->toEmail()->variables)->toHaveKey('has_pro_logo', true);
    expect($empty->toEmail()->variables)->toHaveKey('has_pro_logo', false);
    expect($without->toEmail()->variables)->toHaveKey('has_pro_logo', false);
    expect($with->toArray())->not->toHaveKey('has_pro_logo');
})->with([
    'unclaimed demand reminder' => [
        MarketplaceUnclaimedDemandReminderNotificationData::class,
        ['base_url' => 'https://example.test', 'demand_id' => 1, 'title' => 'T', 'workfield_label' => 'Toiture'],
    ],
    'assignation activation' => [
        MarketplaceAssignationActivationNotificationData::class,
        ['base_url' => 'https://example.test', 'demand_id' => 1],
    ],
]);
