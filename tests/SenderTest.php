<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidEnvelopeException;
use Deegitalbe\TrustupIoNotificationsContracts\Request\ReplyTo;
use Deegitalbe\TrustupIoNotificationsContracts\Request\RequestPayload;
use Deegitalbe\TrustupIoNotificationsContracts\Request\Sender;

/** @return array<string, mixed> */
function requestWithSender(mixed $sender): array
{
    return [
        'type' => NotificationType::T360OnboardingInvitationNotification->value,
        'recipient' => ['identified' => false, 'email' => 'marie@example.test', 'locale' => 'fr-BE', 'source' => 't360'],
        'data' => ['onboarding_email_id' => 'id', 'subject' => 'S', 'html_body' => '<p>H</p>', 'text_body' => 'T'],
        'channels' => null,
        'sender' => $sender,
    ];
}

it('round-trips a full sender with its reply-to', function (): void {
    $sender = new Sender(
        email: 'onboarding@trustup.pro',
        name: 'Trustup Onboarding',
        replyTo: new ReplyTo(email: 'onboarding@trustup.pro', name: 'Trustup Onboarding'),
    );

    expect($sender->toArray())->toBe([
        'email' => 'onboarding@trustup.pro',
        'name' => 'Trustup Onboarding',
        'reply_to' => ['email' => 'onboarding@trustup.pro', 'name' => 'Trustup Onboarding'],
    ])->and(Sender::fromArray($sender->toArray()))->toEqual($sender);
});

it('keeps every sender part optional', function (): void {
    $sender = Sender::fromArray([]);

    expect($sender->email)->toBeNull()
        ->and($sender->name)->toBeNull()
        ->and($sender->replyTo)->toBeNull()
        ->and($sender->toArray())->toBe(['email' => null, 'name' => null, 'reply_to' => null]);
});

it('refuses a malformed sender address', function (array $sender): void {
    expect(fn () => Sender::fromArray($sender))->toThrow(InvalidEnvelopeException::class);
})->with([
    'from' => [['email' => 'not-an-address']],
    'reply-to' => [['reply_to' => ['email' => 'not-an-address']]],
    'reply-to not an object' => [['reply_to' => 'onboarding@trustup.pro']],
]);

it('carries the sender on a request and reads it back', function (): void {
    $payload = RequestPayload::fromArray(requestWithSender(['email' => 'onboarding@trustup.pro', 'reply_to' => ['email' => 'help@trustup.pro']]));

    expect($payload->sender?->email)->toBe('onboarding@trustup.pro')
        ->and($payload->sender?->replyTo?->email)->toBe('help@trustup.pro')
        ->and(RequestPayload::fromArray($payload->toArray())->sender)->toEqual($payload->sender);
});

it('reads a request without sender as no override', function (): void {
    $data = requestWithSender(null);
    unset($data['sender']);

    $payload = RequestPayload::fromArray($data);

    expect($payload->sender)->toBeNull()
        ->and($payload->toArray()['sender'])->toBeNull();
});

it('refuses a request whose sender is not an object', function (): void {
    expect(fn () => RequestPayload::fromArray(requestWithSender('onboarding@trustup.pro')))
        ->toThrow(InvalidEnvelopeException::class);
});

it('refuses a request whose sender address is malformed', function (): void {
    expect(fn () => RequestPayload::fromArray(requestWithSender(['email' => 'nope'])))
        ->toThrow(InvalidEnvelopeException::class);
});
