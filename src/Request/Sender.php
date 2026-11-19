<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Request;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\Serializable;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidEnvelopeException;

readonly class Sender implements Serializable
{
    public function __construct(
        public ?string $email = null,
        public ?string $name = null,
        public ?ReplyTo $replyTo = null,
    ) {
        if ($this->email !== null && filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidEnvelopeException("Sender address [{$this->email}] is not a valid email address.");
        }
    }

    /** @return array{email: ?string, name: ?string, reply_to: array{email: ?string, name: ?string}|null} */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'reply_to' => $this->replyTo?->toArray(),
        ];
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        $replyTo = $data['reply_to'] ?? null;

        if ($replyTo !== null && ! is_array($replyTo)) {
            throw new InvalidEnvelopeException('Sender reply_to must be an object.');
        }

        return new self(
            email: isset($data['email']) ? (string) $data['email'] : null,
            name: isset($data['name']) ? (string) $data['name'] : null,
            replyTo: $replyTo !== null ? ReplyTo::fromArray($replyTo) : null,
        );
    }
}
