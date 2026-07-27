<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Enums\EmailTemplateLocaleGranularity;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\NotificationType;
use Deegitalbe\TrustupIoNotificationsContracts\Tests\Stubs\DefaultRenderingStub;
use Deegitalbe\TrustupIoNotificationsContracts\Tests\Stubs\OverriddenRenderingStub;
use Illuminate\Support\Facades\Lang;

beforeEach(function (): void {
    $key = NotificationType::ToolsTestNotification->slug();
    Lang::addLines([
        "notifications.{$key}.sms" => 'SMS :title',
        "notifications.{$key}.push.title" => 'Push :title',
        "notifications.{$key}.push.body" => 'Push :body',
    ], 'en');
    app()->setLocale('en');
});

it('email content defaults to all constructor data', function (): void {
    $data = new DefaultRenderingStub('Hello', 'World');

    expect($data->toEmail()->variables)->toBe(['title' => 'Hello', 'body' => 'World']);
});

it('email template alias defaults to the notificationType value in kebab-case', function (): void {
    $data = new DefaultRenderingStub('Hello', 'World');

    expect($data->emailTemplate())->toBe('tools-test-notification');
});

it('email template locale granularity defaults to language', function (): void {
    $data = new DefaultRenderingStub('Hello', 'World');

    expect($data->emailTemplateLocaleGranularity())->toBe(EmailTemplateLocaleGranularity::Language);
});

it('allows overriding the email template alias', function (): void {
    expect((new OverriddenRenderingStub('a', 'b'))->emailTemplate())->toBe('custom-alias');
});

it('sms body defaults to translation key built from notificationType with constructor params', function (): void {
    $data = new DefaultRenderingStub('Hi', 'There');

    expect($data->toSms()->body)->toBe('SMS Hi');
});

it('push title and body default to translation keys, data defaults to constructor data', function (): void {
    $data = new DefaultRenderingStub('T', 'B');
    $push = $data->toPush();

    expect($push->title)->toBe('Push T')
        ->and($push->body)->toBe('Push B')
        ->and($push->data)->toBe(['title' => 'T', 'body' => 'B']);
});

it('allows overriding sms body', function (): void {
    expect((new OverriddenRenderingStub('a', 'b'))->toSms()->body)->toBe('custom sms');
});

it('allows overriding push title, body and data', function (): void {
    $push = (new OverriddenRenderingStub('a', 'b'))->toPush();

    expect($push->title)->toBe('custom title')
        ->and($push->body)->toBe('custom body')
        ->and($push->data)->toBe(['custom' => true]);
});
