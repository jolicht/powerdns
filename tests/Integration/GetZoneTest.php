<?php

declare(strict_types=1);

namespace Jolicht\Powerdns\Tests\Integration;

use Jolicht\Powerdns\Api\CreateZone;
use Jolicht\Powerdns\Api\DeleteZone;
use Jolicht\Powerdns\Api\GetZone;
use Jolicht\Powerdns\Api\HttpCreateZone;
use Jolicht\Powerdns\Api\HttpDeleteZone;
use Jolicht\Powerdns\Api\HttpGetZone;
use Jolicht\Powerdns\Dto\CreateZoneDto;
use Jolicht\Powerdns\Model\Record;
use Jolicht\Powerdns\Model\RecordSet;
use Jolicht\Powerdns\Service\RandomSalt;
use Jolicht\Powerdns\ValueObject\Kind;
use Jolicht\Powerdns\ValueObject\Nameserver;
use Jolicht\Powerdns\ValueObject\Nsec3\HashAlgorithm;
use Jolicht\Powerdns\ValueObject\Nsec3Param;
use Jolicht\Powerdns\ValueObject\RecordSetName;
use Jolicht\Powerdns\ValueObject\Type;
use Jolicht\Powerdns\ValueObject\ZoneId;
use Jolicht\Powerdns\ValueObject\ZoneName;

/**
 * @coversNothing
 */
final class GetZoneTest extends HttpApiTestCase
{
    private CreateZone $createZone;
    private DeleteZone $deleteZone;
    private GetZone $getZone;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createZone = new HttpCreateZone($this->httpClient);
        $this->deleteZone = new HttpDeleteZone($this->httpClient);
        $this->getZone = new HttpGetZone($this->httpClient);

        $this->cleanUp();
    }

    public function testCreateZoneReturnsCreatedZone(): void
    {
        $salt = (new RandomSalt())(8);

        $createZoneDto = new CreateZoneDto(
            ZoneName::fromString('example.at.'),
            Kind::NATIVE,
            [
                new RecordSet(RecordSetName::fromString('www.example.at.'), Type::A, 1800, [
                        new Record('127.0.0.1'),
                        new Record('127.0.0.2'),
                    ]
                ),
            ],
            [
                Nameserver::fromString('ns1.test.at.'),
                Nameserver::fromString('ns2.test.at.'),
            ],
            true,
            new Nsec3Param(
                HashAlgorithm::SHA1, 0, 2, $salt
            )
        );
        $this->createZone->__invoke($createZoneDto);

        $zone = $this->getZone->__invoke(ZoneId::fromString('example.at.'));

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

    public function tearDown(): void
    {
        $this->cleanUp();
    }

    private function cleanUp()
    {
        try {
            $this->deleteZone->__invoke(ZoneId::fromString('example.at.'));
        } catch (\Throwable) {
        }
    }
}
