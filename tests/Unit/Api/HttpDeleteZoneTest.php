<?php

declare(strict_types=1);

namespace Jolicht\Powerdns\Tests\Unit\Api;

use Jolicht\Powerdns\Api\HttpDeleteZone;
use Jolicht\Powerdns\Exception\InternalServerErrorException;
use Jolicht\Powerdns\ValueObject\ZoneId;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
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
            ->willReturn(Response::HTTP_NO_CONTENT);

        $this->deleteZone->__invoke(ZoneId::fromString('test.at.'));
    }

    public function testInvokeInvalidResponseCodeThrowsException(): void
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

        $this->expectException(InternalServerErrorException::class);

        $this->deleteZone->__invoke(ZoneId::fromString('test.at.'));
    }
}
