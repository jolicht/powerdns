<?php

namespace Jolicht\Powerdns\Tests\Unit\Exception;

use Jolicht\Powerdns\Exception\ServerException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jolicht\Powerdns\Exception\ServerException
 */
class ServerExceptionTest extends TestCase
{
    public function testGetMessages(): void
    {
        $serverException = $this->getMockForAbstractClass(ServerException::class, [
            'error message',
            404,
            [
                'first errror',
                'second error',
            ],
        ]);
        $this->assertSame([
            'first errror',
            'second error',
        ], $serverException->getMessages());
    }

    public function testGetMessagesDefaultsToEmptyArray(): void
    {
        $serverException = $this->getMockForAbstractClass(ServerException::class, [
            'error message',
            404,
        ]);

        $this->assertSame([], $serverException->getMessages());
    }
}
