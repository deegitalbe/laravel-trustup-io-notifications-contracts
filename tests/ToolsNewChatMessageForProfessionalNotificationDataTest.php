<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\ToolsNewChatMessageForProfessionalNotificationData;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;

it('builds ToolsNewChatMessageForProfessionalNotificationData from its fields', function (): void {
    $data = new ToolsNewChatMessageForProfessionalNotificationData(
        demand_id: 123,
        demand_professional_id: 4242,
    );

    expect($data->demand_id)->toBe(123);
    expect($data->demand_professional_id)->toBe(4242);
});

it('carries every field in the serialized payload', function (): void {
    $data = new ToolsNewChatMessageForProfessionalNotificationData(
        demand_id: 123,
        demand_professional_id: 4242,
    );

    expect($data->toArray())
        ->toHaveKey('demand_id', 123)
        ->toHaveKey('demand_professional_id', 4242);
});

it('round-trips ToolsNewChatMessageForProfessionalNotificationData via toArray and fromArray', function (): void {
    $original = new ToolsNewChatMessageForProfessionalNotificationData(
        demand_id: 123,
        demand_professional_id: 4242,
    );

    $restored = ToolsNewChatMessageForProfessionalNotificationData::fromArray($original->toArray());

    expect($restored->demand_id)->toBe($original->demand_id);
    expect($restored->demand_professional_id)->toBe($original->demand_professional_id);
});

it('reports the tools new-chat-message-for-professional notification type', function (): void {
    $data = new ToolsNewChatMessageForProfessionalNotificationData(
        demand_id: 123,
        demand_professional_id: 4242,
    );

    expect($data->notificationType())->toBe(NotificationType::ToolsNewChatMessageForProfessionalNotification);
});
