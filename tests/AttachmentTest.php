<?php

declare(strict_types=1);

use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidEnvelopeException;
use Deegitalbe\TrustupIoNotificationsContracts\Request\Attachment;

it('round-trips an attachment without content id via toArray and fromArray', function (): void {
    $content = base64_encode("BEGIN:VCALENDAR\nEND:VCALENDAR");
    $original = new Attachment(
        name: 'invite.ics',
        contentType: 'text/calendar; method=REQUEST; charset=UTF-8',
        content: $content,
    );

    $restored = Attachment::fromArray($original->toArray());

    expect($original->toArray())->toBe([
        'name' => 'invite.ics',
        'content_type' => 'text/calendar; method=REQUEST; charset=UTF-8',
        'content' => $content,
        'content_id' => null,
    ]);
    expect($restored)->toEqual($original);
    expect($restored->contentId)->toBeNull();
});

it('keeps the cid prefix of an embedded image content id after a round trip', function (): void {
    $original = new Attachment(
        name: 'payment-qr.png',
        contentType: 'image/png',
        content: base64_encode('png-bytes'),
        contentId: 'cid:payment-qr.png',
    );

    $restored = Attachment::fromArray($original->toArray());

    expect($restored->contentId)->toBe('cid:payment-qr.png');
    expect($restored)->toEqual($original);
});

it('rejects an attachment without a name', function (): void {
    $data = ['content_type' => 'image/png', 'content' => base64_encode('png-bytes')];

    expect(fn () => Attachment::fromArray($data))->toThrow(InvalidEnvelopeException::class);
});

it('rejects an attachment without a content type', function (): void {
    $data = ['name' => 'invite.ics', 'content' => base64_encode('ics-bytes')];

    expect(fn () => Attachment::fromArray($data))->toThrow(InvalidEnvelopeException::class);
});

it('rejects an attachment without a content', function (): void {
    $data = ['name' => 'invite.ics', 'content_type' => 'text/calendar'];

    expect(fn () => Attachment::fromArray($data))->toThrow(InvalidEnvelopeException::class);
});

it('rejects an attachment whose content is not base64', function (): void {
    $data = ['name' => 'invite.ics', 'content_type' => 'text/calendar', 'content' => 'pas du base64!'];

    expect(fn () => Attachment::fromArray($data))->toThrow(InvalidEnvelopeException::class);
});
