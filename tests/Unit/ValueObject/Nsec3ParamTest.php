<?php

declare(strict_types=1);

namespace Jolicht\PowerdnsBundle\Tests\Unit\ValueObject;

use Jolicht\PowerdnsBundle\ValueObject\Nsec3\HashAlgorithm;
use Jolicht\PowerdnsBundle\ValueObject\Nsec3Param;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jolicht\PowerdnsBundle\ValueObject\Nsec3Param
 */
class Nsec3ParamTest extends TestCase
{
    private Nsec3Param $nsec3Param;

    protected function setUp(): void
    {
        $this->nsec3Param = new Nsec3Param(
            HashAlgorithm::SHA1,
            1,
            2,
            '449e49195e1d0d2e'
        );
    }

    public function testGetHashAlgorithm(): void
    {
        $this->assertSame(HashAlgorithm::SHA1, $this->nsec3Param->getHashAlgorithm());
    }

    public function testGetFlags(): void
    {
        $this->assertSame(1, $this->nsec3Param->getFlags());
    }

    public function testGetIterations(): void
    {
        $this->assertSame(2, $this->nsec3Param->getIterations());
    }

    public function testGetSalt(): void
    {
        $this->assertSame('449e49195e1d0d2e', $this->nsec3Param->getSalt());
    }

    public function testToString(): void
    {
        $this->assertSame('1 1 2 449e49195e1d0d2e', $this->nsec3Param->toString());
    }

    public function testStringableToString(): void
    {
        $this->assertSame('1 1 2 449e49195e1d0d2e', (string) $this->nsec3Param);
    }

    public function testFromString(): void
    {
        $this->assertEquals($this->nsec3Param, Nsec3Param::fromString('1 1 2 449e49195e1d0d2e'));
    }

    public function testFromStringEmptyStringReturnsNull(): void
    {
        $this->assertNull(Nsec3Param::fromString(''));
    }
}
