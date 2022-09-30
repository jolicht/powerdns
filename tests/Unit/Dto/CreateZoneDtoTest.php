<?php

declare(strict_types=1);

namespace Jolicht\Powerdns\Tests\Unit\Dto;

use Jolicht\Powerdns\Dto\CreateZoneDto;
use Jolicht\Powerdns\Model\RecordSet;
use Jolicht\Powerdns\ValueObject\Kind;
use Jolicht\Powerdns\ValueObject\Nameserver;
use Jolicht\Powerdns\ValueObject\Nsec3\HashAlgorithm;
use Jolicht\Powerdns\ValueObject\Nsec3Param;
use Jolicht\Powerdns\ValueObject\RecordSetName;
use Jolicht\Powerdns\ValueObject\Type;
use Jolicht\Powerdns\ValueObject\ZoneName;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jolicht\Powerdns\Dto\CreateZoneDto
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
