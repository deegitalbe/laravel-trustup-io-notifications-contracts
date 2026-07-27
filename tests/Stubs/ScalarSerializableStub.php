<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Tests\Stubs;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\Serializable;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\SerializesFromConstructor;

final readonly class ScalarSerializableStub implements Serializable
{
    use SerializesFromConstructor;

    /** @param array<string, mixed> $meta */
    public function __construct(
        public string $title,
        public int $count,
        public array $meta = [],
        public ?string $note = null,
    ) {}
}
