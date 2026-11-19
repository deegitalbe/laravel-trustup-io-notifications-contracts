<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Request;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\Serializable;
use Deegitalbe\TrustupIoNotificationsContracts\Exceptions\InvalidEnvelopeException;

readonly class Attachment implements Serializable
{
    public function __construct(
        public string $name,
        public string $contentType,
        public string $content,
        public ?string $contentId = null,
    ) {
        if ($this->name === '' || $this->contentType === '' || $this->content === '') {
            throw new InvalidEnvelopeException('Attachment must have a name, a content type and a content.');
        }

        if (base64_decode($this->content, strict: true) === false) {
            throw new InvalidEnvelopeException("Attachment [{$this->name}] content is not valid base64.");
        }
    }

    /** @return array{name: string, content_type: string, content: string, content_id: ?string} */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'content_type' => $this->contentType,
            'content' => $this->content,
            'content_id' => $this->contentId,
        ];
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            name: (string) ($data['name'] ?? ''),
            contentType: (string) ($data['content_type'] ?? ''),
            content: (string) ($data['content'] ?? ''),
            contentId: isset($data['content_id']) ? (string) $data['content_id'] : null,
        );
    }
}
