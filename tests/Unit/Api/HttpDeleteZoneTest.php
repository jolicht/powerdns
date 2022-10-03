<?php

declare(strict_types=1);

namespace Jolicht\Powerdns\Tests\Unit\Api;

use InvalidArgumentException;
use Jolicht\Powerdns\Api\HttpDeleteZone;
use Jolicht\Powerdns\ValueObject\HttpStatusCode;
use Jolicht\Powerdns\ValueObject\ZoneId;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @covers \Jolicht\Powerdns\Api\HttpDeleteZone
 */
class HttpDeleteZoneTest extends TestCase
{
    private HttpClientInterface $client;
    private HttpDeleteZone $deleteZone;

    protected function setUp(): void
    {
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->deleteZone = new HttpDeleteZone($this->client);
    }

    public function testInvoke(): void
    {
        $response = $this->createMock(ResponseInterface::class);

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->identicalTo('DELETE'),
                $this->identicalTo('zones/test.at.')
            )->willReturn($response);

        $response
            ->method('getStatusCode')
            ->willReturn(HttpStatusCode::HTTP_NO_CONTENT->value);

        $this->deleteZone->__invoke(ZoneId::fromString('test.at.'));
    }

    public function testInvokeInvalidResponseCodeThrowsInvalidArgumentException(): void
    {
        $response = $this->createMock(ResponseInterface::class);

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->identicalTo('DELETE'),
                $this->identicalTo('zones/test.at.')
            )->willReturn($response);

        $response
            ->method('getStatusCode')
            ->willReturn(500);

        $this->expectException(InvalidArgumentException::class);

        $this->deleteZone->__invoke(ZoneId::fromString('test.at.'));
    }
}
