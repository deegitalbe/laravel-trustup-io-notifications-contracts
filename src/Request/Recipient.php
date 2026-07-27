<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Request;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\Serializable;
use Deegitalbe\TrustupIoNotificationsContracts\Enums\Source;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidRecipientException;
use Deegitalbe\TrustupIoNotificationsContracts\Support\LocaleNormalizer;

readonly class Recipient implements Serializable
{
    /** @param list<string> $deviceTokens */
    private function __construct(
        public ?Source $source,
        public ?string $externalUserId,
        public ?string $email,
        public ?string $phone,
        public array $deviceTokens,
        private bool $identified,
        public ?string $locale,
    ) {}

    public static function identified(string $externalUserId, ?Source $source = null): self
    {
        return new self(
            source: $source,
            externalUserId: $externalUserId,
            email: null,
            phone: null,
            deviceTokens: [],
            identified: true,
            locale: null,
        );
    }

    /** @param list<string> $deviceTokens */
    public static function anonymous(?string $email, ?string $phone, array $deviceTokens, ?string $locale = null): self
    {
        $hasCoordinate = $email !== null || $phone !== null || $deviceTokens !== [];

        if (! $hasCoordinate) {
            throw new InvalidRecipientException('Anonymous recipient must have at least one coordinate (email, phone, or device_tokens).');
        }

        $canonicalLocale = LocaleNormalizer::normalize($locale);

        if ($canonicalLocale === null) {
            throw new InvalidRecipientException('Anonymous recipient must have a valid, normalisable locale.');
        }

        return new self(
            source: null,
            externalUserId: null,
            email: $email,
            phone: $phone,
            deviceTokens: $deviceTokens,
            identified: false,
            locale: $canonicalLocale,
        );
    }

    public function isIdentified(): bool
    {
        return $this->identified;
    }

    public function withSource(Source $source): self
    {
        return new self(
            source: $source,
            externalUserId: $this->externalUserId,
            email: $this->email,
            phone: $this->phone,
            deviceTokens: $this->deviceTokens,
            identified: $this->identified,
            locale: $this->locale,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        $array = $this->identified
            ? [
                'identified' => true,
                'external_user_id' => $this->externalUserId,
            ]
            : [
                'identified' => false,
                'email' => $this->email,
                'phone' => $this->phone,
                'device_tokens' => $this->deviceTokens,
                'locale' => $this->locale,
            ];

        if ($this->source !== null) {
            $array['source'] = $this->source->value;
        }

        return $array;
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        $hasIdentity = isset($data['external_user_id']);
        $hasAnonymous = isset($data['email']) || isset($data['phone']) || (isset($data['device_tokens']) && $data['device_tokens'] !== []);

        if ($hasIdentity && $hasAnonymous) {
            throw new InvalidRecipientException('Recipient cannot mix identified (source/external_user_id) and anonymous (email/phone/device_tokens) coordinates.');
        }

        if ($hasIdentity) {
            if (! isset($data['source'])) {
                throw new InvalidRecipientException('Identified recipient must have a resolved source.');
            }

            return self::identified(
                externalUserId: (string) ($data['external_user_id'] ?? ''),
                source: Source::from((string) $data['source']),
            );
        }

        $anonymous = self::anonymous(
            email: isset($data['email']) ? (string) $data['email'] : null,
            phone: isset($data['phone']) ? (string) $data['phone'] : null,
            deviceTokens: isset($data['device_tokens']) ? (array) $data['device_tokens'] : [],
            locale: isset($data['locale']) ? (string) $data['locale'] : null,
        );

        if (isset($data['source'])) {
            $anonymous = $anonymous->withSource(Source::from((string) $data['source']));
        }

        return $anonymous;
    }
}
