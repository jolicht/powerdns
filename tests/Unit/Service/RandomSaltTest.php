<?php

declare(strict_types=1);

namespace Jolicht\Powerdns\Tests\Unit\Service;

use Jolicht\Powerdns\Service\RandomSalt;
use PHPUnit\Framework\TestCase;

use function strlen;

/**
 * @covers \Jolicht\Powerdns\Service\RandomSalt
 */
class RandomSaltTest extends TestCase
{
    public function testInvoke(): void
    {
        $randomSalt = new RandomSalt();
        $salt = $randomSalt(8);
        $this->assertIsString($salt);
        $this->assertSame(16, strlen($salt));
    }
}
