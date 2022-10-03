<?php

declare(strict_types=1);

namespace Jolicht\PowerdnsBundle\Tests\Unit\Api;

use InvalidArgumentException;
use Jolicht\PowerdnsBundle\Api\HttpCreateZone;
use Jolicht\PowerdnsBundle\Dto\CreateZoneDto;
use Jolicht\PowerdnsBundle\Model\Zone;
use Jolicht\PowerdnsBundle\ValueObject\Kind;
use Jolicht\PowerdnsBundle\ValueObject\ZoneName;

use function json_encode;

use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @covers \Jolicht\PowerdnsBundle\Api\HttpCreateZone
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

    public function testInvoke(): void
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

        $createZoneDto = new CreateZoneDto(
            ZoneName::fromString('example.at.'),
            Kind::NATIVE,
        );

        $this->expectException(InvalidArgumentException::class);

        $this->createZone->__invoke($createZoneDto);
    }
}
