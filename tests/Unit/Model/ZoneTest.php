<?php

declare(strict_types=1);

namespace Jolicht\PowerdnsBundle\Tests\Unit\Model;

use Jolicht\PowerdnsBundle\Model\RecordSet;
use Jolicht\PowerdnsBundle\Model\Zone;
use Jolicht\PowerdnsBundle\ValueObject\Kind;
use Jolicht\PowerdnsBundle\ValueObject\Nsec3\HashAlgorithm;
use Jolicht\PowerdnsBundle\ValueObject\Nsec3Param;
use Jolicht\PowerdnsBundle\ValueObject\RecordSetName;
use Jolicht\PowerdnsBundle\ValueObject\Type;
use Jolicht\PowerdnsBundle\ValueObject\ZoneId;
use Jolicht\PowerdnsBundle\ValueObject\ZoneName;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jolicht\PowerdnsBundle\Model\Zone
 */
class ZoneTest extends TestCase
{
    private ZoneId $zoneId;
    private ZoneName $zoneName;
    private Kind $kind;
    private Nsec3Param $nsec3Param;
    private array $recordSets;
    private Zone $zone;

    protected function setUp(): void
    {
        $this->zoneId = ZoneId::fromString('id.at.');
        $this->zoneName = ZoneName::fromString('name.at.');
        $this->kind = Kind::NATIVE;
        $this->nsec3Param = new Nsec3Param(
            HashAlgorithm::SHA1,
            1,
            2
        );
        $this->recordSets = [
            new RecordSet(RecordSetName::fromString('www.test.at'), Type::A, 7200, []),
            new RecordSet(RecordSetName::fromString('cname.test.at'), Type::CNAME, 900, []),
        ];

        $this->zone = new Zone(
            $this->zoneId,
            $this->zoneName,
            $this->kind,
            2022071411,
            '/api/v1/servers/localhost/zones/test.at.',
            true,
            $this->nsec3Param,
            $this->recordSets
        );
    }

    public function testGetId(): void
    {
        $this->assertSame($this->zoneId, $this->zone->getId());
    }

    public function testGetName(): void
    {
        $this->assertSame($this->zoneName, $this->zone->getName());
    }

    public function testGetKind(): void
    {
        $this->assertSame($this->kind, $this->zone->getKind());
    }

    public function testGetSerial(): void
    {
        $this->assertSame(2022071411, $this->zone->getSerial());
    }

    public function testGetUrl(): void
    {
        $this->assertSame('/api/v1/servers/localhost/zones/test.at.', $this->zone->getUrl());
    }

    public function testIsDnssecSigned(): void
    {
        $this->assertTrue($this->zone->isDnssecSigned());
    }

    public function testGetNsec3Param(): void
    {
        $this->assertSame($this->nsec3Param, $this->zone->getNsec3Param());
    }

    public function testGetRecordSets(): void
    {
        $this->assertSame($this->recordSets, $this->zone->getRecordSets());
    }

    public function testFromArray(): void
    {
        $data = [
            'id' => 'id.at.',
            'name' => 'name.at.',
            'kind' => 'Native',
            'serial' => '2022071411',
            'url' => '/api/v1/servers/localhost/zones/test.at.',
            'dnssec' => true,
            'nsec3param' => '1 1 2 -',
            'rrsets' => [
                [
                    'name' => 'www.test.at.',
                    'type' => 'A',
                    'ttl' => 7200,
                    'records' => [],
                ],
                [
                    'name' => 'cname.test.at.',
                    'type' => 'CNAME',
                    'ttl' => 900,
                    'records' => [],
                ],
            ],
        ];

        $this->assertEquals($this->zone, Zone::fromArray($data));
    }
}
