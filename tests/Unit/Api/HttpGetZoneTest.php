<?php

declare(strict_types=1);

namespace Jolicht\Powerdns\Tests\Unit\Api;

use InvalidArgumentException;
use Jolicht\Powerdns\Api\HttpGetZone;
use Jolicht\Powerdns\Model\Zone;
use Jolicht\Powerdns\ValueObject\ZoneId;

use function json_encode;

use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @covers \Jolicht\Powerdns\Api\HttpGetZone
 */
class HttpGetZoneTest extends TestCase
{
    private HttpClientInterface $client;
    private HttpGetZone $getZone;

    protected function setUp(): void
    {
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->getZone = new HttpGetZone($this->client);
    }

    public function testInvoke(): void
    {
        $response = $this->createMock(ResponseInterface::class);

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->identicalTo('GET'),
                $this->identicalTo('zones/test.at.')
            )
            ->willReturn($response);

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

        $zone = $this->getZone->__invoke(ZoneId::fromString('test.at.'));
        $this->assertInstanceOf(Zone::class, $zone);
    }

    public function testInvokeNonDecodableResponseThrowsInvalidArgumentException(): void
    {
        $response = $this->createMock(ResponseInterface::class);

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->identicalTo('GET'),
                $this->identicalTo('zones/test.at.')
            )
            ->willReturn($response);

        $response
            ->method('getContent')
            ->willReturn('invalid');

        $this->expectException(InvalidArgumentException::class);

        $zone = $this->getZone->__invoke(ZoneId::fromString('test.at.'));
    }
}
