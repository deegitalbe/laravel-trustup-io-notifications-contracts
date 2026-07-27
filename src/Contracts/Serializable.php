<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Contracts;

interface Serializable
{
    /** @return array<string, mixed> */
    public function toArray(): array;

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self;
}
