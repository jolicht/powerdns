<?php

declare(strict_types=1);

namespace Jolicht\PowerdnsBundle\Tests\Unit\Dto;

use Jolicht\PowerdnsBundle\Dto\CreateZoneDto;
use Jolicht\PowerdnsBundle\Model\RecordSet;
use Jolicht\PowerdnsBundle\ValueObject\Kind;
use Jolicht\PowerdnsBundle\ValueObject\Nameserver;
use Jolicht\PowerdnsBundle\ValueObject\Nsec3\HashAlgorithm;
use Jolicht\PowerdnsBundle\ValueObject\Nsec3Param;
use Jolicht\PowerdnsBundle\ValueObject\RecordSetName;
use Jolicht\PowerdnsBundle\ValueObject\Type;
use Jolicht\PowerdnsBundle\ValueObject\ZoneName;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jolicht\PowerdnsBundle\Dto\CreateZoneDto
 */
class CreateZoneDtoTest extends TestCase
{
    private CreateZoneDto $dto;

    protected function setUp(): void
    {
        $this->dto = new CreateZoneDto(
            ZoneName::fromString('example.at.'),
            Kind::NATIVE,
            [
                Nameserver::fromString('ns1.test.at.'),
                Nameserver::fromString('ns2.test.at.'),
            ],
            true,
            new Nsec3Param(
                HashAlgorithm::SHA1, 0, 2
            ),
            [
                new RecordSet(RecordSetName::fromString('www.example.at.'), Type::A, 1800, []),
            ]
        );
    }

    public function testJsonSerialize(): void
    {
        $expected = [
            'name' => 'example.at.',
            'kind' => 'Native',
            'dnssec' => true,
            'nsec3param' => '1 0 2 -',
            'nameservers' => [
                'ns1.test.at.',
                'ns2.test.at.',
            ],
            'rrsets' => [
                [
                    'name' => 'www.example.at.',
                    'type' => 'A',
                    'ttl' => 1800,
                    'records' => [],
                ],
            ],
        ];
        $this->assertSame($expected, $this->dto->jsonSerialize());
    }
}
