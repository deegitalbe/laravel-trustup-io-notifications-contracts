<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Tests\Stubs;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\Serializable;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\SerializesFromConstructor;

/** Holds a nested Serializable to exercise recursive toArray(). */
final readonly class NestingSerializableStub implements Serializable
{
    use SerializesFromConstructor;

    public function __construct(
        public string $label,
        public NestedSerializableStub $child,
    ) {}
}
