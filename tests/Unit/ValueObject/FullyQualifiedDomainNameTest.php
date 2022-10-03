<?php

declare(strict_types=1);

namespace Jolicht\PowerdnsBundle\Tests\Unit\ValueObject;

use Jolicht\PowerdnsBundle\Tests\Unit\ValueObject\_files\ConcreteFullyQualifiedDomainName;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jolicht\PowerdnsBundle\ValueObject\FullyQualifiedDomainName
 */
class FullyQualifiedDomainNameTest extends TestCase
{
    public function testFromString(): void
    {
        $fullyQualifiedDomainName = ConcreteFullyQualifiedDomainName::fromString('test.at.');
        $this->assertInstanceOf(ConcreteFullyQualifiedDomainName::class, $fullyQualifiedDomainName);
    }

    public function testFromStringAddsTrailingDotIfNotFullyQualified(): void
    {
        $fullyQualifiedDomainName = ConcreteFullyQualifiedDomainName::fromString('www.test.at');
        $this->assertSame('www.test.at.', $fullyQualifiedDomainName->toString());
    }

    public function testFromStringCanStartWithUnderScore(): void
    {
        $fullyQualifiedDomainName = ConcreteFullyQualifiedDomainName::fromString('_x.test.at');
        $this->assertInstanceOf(ConcreteFullyQualifiedDomainName::class, $fullyQualifiedDomainName);
        $this->assertSame('_x.test.at.', $fullyQualifiedDomainName->toString());
    }

    public function testFromStringCanStartWithAsteriskAndDot(): void
    {
        $fullyQualifiedDomainName = ConcreteFullyQualifiedDomainName::fromString('*.test.at');
        $this->assertInstanceOf(ConcreteFullyQualifiedDomainName::class, $fullyQualifiedDomainName);
        $this->assertSame('*.test.at.', $fullyQualifiedDomainName->toString());
    }

    public function testToString(): void
    {
        $fullyQualifiedDomainName = ConcreteFullyQualifiedDomainName::fromString('test.at.');
        $this->assertSame('test.at.', $fullyQualifiedDomainName->toString());
    }

    public function testStringableToString(): void
    {
        $fullyQualifiedDomainName = ConcreteFullyQualifiedDomainName::fromString('test.at.');
        $this->assertSame('test.at.', (string) $fullyQualifiedDomainName);
    }
}
