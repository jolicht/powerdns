<?php

declare(strict_types=1);

namespace Jolicht\PowerdnsBundle\ValueObject;

use function rtrim;
use function str_starts_with;

use Stringable;

use function substr;

use Webmozart\Assert\Assert;

abstract class FullyQualifiedDomainName implements Stringable
{
    final protected function __construct(
        private readonly string $fullyQualifiedName,
    ) {
    }

    public static function fromString(string $name): static
    {
        $name = rtrim($name, '.');

        $pattern = '/^(?!\-)(?:(?:[a-zA-Z\d_][_a-zA-Z\d\-]{0,61})?[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/';

        if (str_starts_with($name, '_')) {
            Assert::regex(substr($name, 1), $pattern);
        } elseif (str_starts_with($name, '*.')) {
            Assert::regex(substr($name, 2), $pattern);
        } else {
            Assert::regex($name, $pattern);
        }

        return new static($name.'.');
    }

    public function toString(): string
    {
        return $this->fullyQualifiedName;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
