<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Data\EmailContent;
use Deegitalbe\TrustupIoNotificationsContracts\Data\PushContent;
use Deegitalbe\TrustupIoNotificationsContracts\Data\SmsContent;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidNotificationDataException;

it('creates SmsContent with a non-empty body', function (): void {
    $content = new SmsContent('Hello from SMS');

    expect($content->body)->toBe('Hello from SMS');
});

it('throws InvalidNotificationDataException when SmsContent body is empty', function (): void {
    expect(fn () => new SmsContent(''))->toThrow(InvalidNotificationDataException::class);
});

it('creates PushContent with title and body', function (): void {
    $content = new PushContent('Title', 'Body text');

    expect($content->title)->toBe('Title')
        ->and($content->body)->toBe('Body text')
        ->and($content->data)->toBe([]);
});

it('creates PushContent with optional data map', function (): void {
    $content = new PushContent('Title', 'Body', ['key' => 'value']);

    expect($content->data)->toBe(['key' => 'value']);
});

it('throws InvalidNotificationDataException when PushContent title is empty', function (): void {
    expect(fn () => new PushContent('', 'Body'))->toThrow(InvalidNotificationDataException::class);
});

it('throws InvalidNotificationDataException when PushContent body is empty', function (): void {
    expect(fn () => new PushContent('Title', ''))->toThrow(InvalidNotificationDataException::class);
});

it('creates EmailContent with a variables map', function (): void {
    $content = new EmailContent(['title' => 'Hello', 'body' => 'World']);

    expect($content->variables)->toBe(['title' => 'Hello', 'body' => 'World']);
});
