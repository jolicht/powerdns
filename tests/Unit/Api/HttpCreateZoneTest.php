<?php

declare(strict_types=1);

namespace Jolicht\Powerdns\Tests\Unit\Api;

use InvalidArgumentException;
use Jolicht\Powerdns\Api\HttpCreateZone;
use Jolicht\Powerdns\Dto\CreateZoneDto;
use Jolicht\Powerdns\Exception\InternalServerErrorException;
use Jolicht\Powerdns\Model\Zone;
use Jolicht\Powerdns\ValueObject\Kind;
use Jolicht\Powerdns\ValueObject\ZoneName;

use function json_encode;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @covers \Jolicht\Powerdns\Api\HttpCreateZone
 */
class HttpCreateZoneTest extends TestCase
{
    private HttpClientInterface $client;
    private HttpCreateZone $createZone;

    protected function setUp(): void
    {
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->createZone = new HttpCreateZone($this->client);
    }

    public function testInvokeReturnsCreatedZone(): void
    {
        $response = $this->createMock(ResponseInterface::class);

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->identicalTo('POST'),
                $this->identicalTo('zones')
            )->willReturn($response);

        $zoneContent = json_encode([
            'id' => 'id.at.',
            'name' => 'name.at.',
            'kind' => 'Native',
            'serial' => '2022071411',
            'url' => '/api/v1/servers/localhost/zones/test.at.',
            'dnssec' => true,
            'nsec3param' => '1 1 2 -',
            'rrsets' => [],
        ]);

        $response
            ->method('getContent')
            ->willReturn($zoneContent);

        $response
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_CREATED);

        $createZoneDto = new CreateZoneDto(
            ZoneName::fromString('example.at.'),
            Kind::NATIVE,
        );
        $zone = $this->createZone->__invoke($createZoneDto);
        $this->assertInstanceOf(Zone::class, $zone);
    }

    public function testInvokeNonDecodableResponseThrowsInvalidArgumentException(): void
    {
        $response = $this->createMock(ResponseInterface::class);

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->identicalTo('POST'),
                $this->identicalTo('zones')
            )->willReturn($response);

        $response
            ->method('getContent')
            ->willReturn('invalid');

        $response
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_CREATED);

        $createZoneDto = new CreateZoneDto(
            ZoneName::fromString('example.at.'),
            Kind::NATIVE,
        );

        $this->expectException(InvalidArgumentException::class);

        $this->createZone->__invoke($createZoneDto);
    }

    public function testInvokeErrorStatusCodeThrowsException(): void
    {
        $response = $this->createMock(ResponseInterface::class);

        $this->client
            ->method('request')
            ->willReturn($response);

        $response
            ->method('getContent')
            ->willReturn('[]');

        $response
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_INTERNAL_SERVER_ERROR);

        $createZoneDto = new CreateZoneDto(
            ZoneName::fromString('example.at.'),
            Kind::NATIVE,
        );

        $this->expectException(InternalServerErrorException::class);

        $this->createZone->__invoke($createZoneDto);
    }
}
