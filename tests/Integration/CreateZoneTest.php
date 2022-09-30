<?php

declare(strict_types=1);

namespace Jolicht\Powerdns\Tests\Integration;

use function bin2hex;

use Jolicht\Powerdns\Api\CreateZone;
use Jolicht\Powerdns\Api\DeleteZone;
use Jolicht\Powerdns\Api\HttpCreateZone;
use Jolicht\Powerdns\Api\HttpDeleteZone;
use Jolicht\Powerdns\Dto\CreateZoneDto;
use Jolicht\Powerdns\Model\Record;
use Jolicht\Powerdns\Model\RecordSet;
use Jolicht\Powerdns\ValueObject\Kind;
use Jolicht\Powerdns\ValueObject\Nameserver;
use Jolicht\Powerdns\ValueObject\Nsec3\HashAlgorithm;
use Jolicht\Powerdns\ValueObject\Nsec3Param;
use Jolicht\Powerdns\ValueObject\RecordSetName;
use Jolicht\Powerdns\ValueObject\Type;
use Jolicht\Powerdns\ValueObject\ZoneId;
use Jolicht\Powerdns\ValueObject\ZoneName;

use function random_bytes;

/**
 * @coversNothing
 */
final class CreateZoneTest extends HttpApiTestCase
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

    public function testCreateZoneReturnsCreatedZone(): void
    {
        $salt = bin2hex(random_bytes(8));

        $createZoneDto = new CreateZoneDto(
            ZoneName::fromString('example.at.'),
            Kind::NATIVE,
            [
                Nameserver::fromString('ns1.test.at.'),
                Nameserver::fromString('ns2.test.at.'),
            ],
            true,
            new Nsec3Param(
                HashAlgorithm::SHA1, 0, 2, $salt
            ),
            [
                new RecordSet(RecordSetName::fromString('www.example.at.'), Type::A, 1800, [
                        new Record('127.0.0.1'),
                        new Record('127.0.0.2'),
                    ]
                ),
            ]
        );
        $zone = $this->createZone->__invoke($createZoneDto);

        $this->assertSame('example.at.', $zone->getId()->toString());
        $this->assertSame('example.at.', $zone->getName()->toString());
        $this->assertSame(Kind::NATIVE, $zone->getKind());
        $this->assertSame('/api/v1/servers/localhost/zones/example.at.', $zone->getUrl());
        $this->assertIsInt($zone->getSerial());
        $this->assertTrue($zone->isDnssecSigned());

        $nsec3Param = $zone->getNsec3Param();
        $this->assertSame(HashAlgorithm::SHA1, $nsec3Param->getHashAlgorithm());
        $this->assertSame(0, $nsec3Param->getFlags());
        $this->assertSame(2, $nsec3Param->getIterations());
        $this->assertSame($salt, $nsec3Param->getSalt());

        $recordSets = $zone->getRecordSets();
        $this->assertCount(3, $recordSets);

        $expectedARecordSet = new RecordSet(RecordSetName::fromString('www.example.at.'), Type::A, 1800, [
                new Record('127.0.0.1'),
                new Record('127.0.0.2'),
            ]
        );
        $this->assertEquals($expectedARecordSet, $recordSets[0]);

        $expectedNsRecordSet = new RecordSet(RecordSetName::fromString('example.at.'), Type::NS, 3600, [
            new Record('ns1.test.at.'),
            new Record('ns2.test.at.'),
        ]);

        $this->assertEquals($expectedNsRecordSet, $recordSets[2]);
    }

    private function cleanUp()
    {
        $this->deleteZone->__invoke(ZoneId::fromString('example.at.'));
    }
}
