<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Request;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\Serializable;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidEnvelopeException;

readonly class ReplyTo implements Serializable
{
    public function __construct(
        public ?string $email = null,
        public ?string $name = null,
    ) {
        if ($this->email !== null && filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidEnvelopeException("Reply-to address [{$this->email}] is not a valid email address.");
        }
    }

    /** @return array{email: ?string, name: ?string} */
    public function toArray(): array
    {
        return ['email' => $this->email, 'name' => $this->name];
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            email: isset($data['email']) ? (string) $data['email'] : null,
            name: isset($data['name']) ? (string) $data['name'] : null,
        );
    }
}
