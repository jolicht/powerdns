<?php

declare(strict_types=1);

namespace Jolicht\PowerdnsBundle\ValueObject;

use function explode;

use Jolicht\PowerdnsBundle\ValueObject\Nsec3\HashAlgorithm;
use Stringable;

use function vsprintf;

use Webmozart\Assert\Assert;

final class Nsec3Param implements Stringable
{
    public function __construct(
        private readonly HashAlgorithm $hashAlgorithm,
        private readonly int $flags,
        private readonly int $iterations,
        private readonly ?string $salt = null
    ) {
        Assert::inArray($this->flags, [
            0b00000000,
            0b00000001,
        ]);
        Assert::range($this->iterations, 0, 65535);

        Assert::nullOrRegex($this->salt, '/^([[:xdigit:]]{1,510})$/');
    }

    public function getHashAlgorithm(): HashAlgorithm
    {
        return $this->hashAlgorithm;
    }

    public function getFlags(): int
    {
        return $this->flags;
    }

    public function getIterations(): int
    {
        return $this->iterations;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return vsprintf('%1$d %2$d %3$d %4$s', [
            $this->hashAlgorithm->value,
            $this->flags,
            $this->iterations,
            $this->salt ?? '-',
        ]);
    }

    public static function fromString(string $value): ?self
    {
        if ('' === $value) {
            return null;
        }

        Assert::regex($value, '/^(\d+) (\d+) (\d+) ([[:xdigit:]]+|-)$/');

        [$hashAlgorithm, $flags, $iterations, $salt] = explode(' ', $value, 4);

        return new self(
            HashAlgorithm::from((int) $hashAlgorithm),
            (int) $flags,
            (int) $iterations,
            ('-' === $salt) ? null : $salt
        );
    }
}
