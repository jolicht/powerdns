<?php

declare(strict_types=1);

namespace Jolicht\PowerdnsBundle\Model;

use JsonSerializable;

final class Record implements JsonSerializable
{
    public function __construct(
        private readonly string $content,
        private readonly bool $disabled = false
    ) {
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            content: (string) $data['content'],
            disabled: (bool) $data['disabled']
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'content' => $this->content,
            'disabled' => $this->disabled,
        ];
    }
}
