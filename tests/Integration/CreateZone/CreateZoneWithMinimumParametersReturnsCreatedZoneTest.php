<?php

namespace Jolicht\Powerdns\Tests\Integration\CreateZone;

use Jolicht\Powerdns\Api\CreateZone;
use Jolicht\Powerdns\Api\DeleteZone;
use Jolicht\Powerdns\Api\HttpCreateZone;
use Jolicht\Powerdns\Api\HttpDeleteZone;
use Jolicht\Powerdns\Dto\CreateZoneDto;
use Jolicht\Powerdns\Tests\Integration\HttpApiTestCase;
use Jolicht\Powerdns\ValueObject\Kind;
use Jolicht\Powerdns\ValueObject\ZoneId;
use Jolicht\Powerdns\ValueObject\ZoneName;

/**
 * @coversNothing
 */
final class CreateZoneWithMinimumParametersReturnsCreatedZoneTest extends HttpApiTestCase
{
    private CreateZone $createZone;
    private DeleteZone $deleteZone;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createZone = new HttpCreateZone($this->httpClient);
        $this->deleteZone = new HttpDeleteZone($this->httpClient);

        $this->cleanUp();
    }

    public function tearDown(): void
    {
        $this->cleanUp();
    }

    public function testCreateZoneWithMinimumParametersReturnsCreatedZone(): void
    {
        $createZoneDto = new CreateZoneDto(
            ZoneName::fromString('example.at.'),
            Kind::NATIVE,
        );
        $zone = $this->createZone->__invoke($createZoneDto);

        $this->assertSame('example.at.', $zone->getId()->toString());
        $this->assertSame('example.at.', $zone->getName()->toString());
        $this->assertSame(Kind::NATIVE, $zone->getKind());
        $this->assertSame('/api/v1/servers/localhost/zones/example.at.', $zone->getUrl());
        $this->assertIsInt($zone->getSerial());
        $this->assertFalse($zone->isDnssecSigned());
        $this->assertNull($zone->getNsec3Param());
    }

    private function cleanUp()
    {
        try {
            $this->deleteZone->__invoke(ZoneId::fromString('example.at.'));
        } catch (\Throwable) {
        }
    }
}
