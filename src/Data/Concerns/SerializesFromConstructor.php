<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\Serializable;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidNotificationDataException;
use ReflectionClass;
use ReflectionObject;

/**
 * Derives toArray()/fromArray() from the constructor parameters by reflection.
 * Every constructor parameter must be a promoted public property. Nested
 * Serializable properties are serialised recursively; on fromArray() a missing
 * key falls back to the constructor default, or null for a nullable parameter,
 * and throws for a required one (the wire boundary fails loud, not with a raw
 * TypeError). Override either method for a non-trivial mapping (enums, value
 * objects).
 */
trait SerializesFromConstructor
{
    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $constructor = (new ReflectionObject($this))->getConstructor();
        $output = [];

        foreach ($constructor?->getParameters() ?? [] as $parameter) {
            $name = $parameter->getName();
            $value = $this->{$name};
            $output[$name] = $value instanceof Serializable ? $value->toArray() : $value;
        }

        return $output;
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        $constructor = (new ReflectionClass(static::class))->getConstructor();
        $arguments = [];

        foreach ($constructor?->getParameters() ?? [] as $parameter) {
            $name = $parameter->getName();

            if (array_key_exists($name, $data)) {
                $arguments[$name] = $data[$name];

                continue;
            }

            if ($parameter->isDefaultValueAvailable()) {
                $arguments[$name] = $parameter->getDefaultValue();

                continue;
            }

            if ($parameter->allowsNull()) {
                $arguments[$name] = null;

                continue;
            }

            throw new InvalidNotificationDataException(
                static::class."::fromArray() is missing required key [{$name}]."
            );
        }

        return new static(...$arguments);
    }
}
