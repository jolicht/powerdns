<?php

namespace Jolicht\Powerdns\Tests\Integration\CreateZone;

use Jolicht\Powerdns\Api\CreateZone;
use Jolicht\Powerdns\Api\DeleteZone;
use Jolicht\Powerdns\Api\HttpCreateZone;
use Jolicht\Powerdns\Api\HttpDeleteZone;
use Jolicht\Powerdns\Dto\CreateZoneDto;
use Jolicht\Powerdns\Exception\UnprocessableEntityException;
use Jolicht\Powerdns\Model\Record;
use Jolicht\Powerdns\Model\RecordSet;
use Jolicht\Powerdns\Tests\Integration\HttpApiTestCase;
use Jolicht\Powerdns\ValueObject\Kind;
use Jolicht\Powerdns\ValueObject\RecordSetName;
use Jolicht\Powerdns\ValueObject\Type;
use Jolicht\Powerdns\ValueObject\ZoneId;
use Jolicht\Powerdns\ValueObject\ZoneName;

/**
 * @coversNothing
 */
final class CreateZoneReturnsNotHttpCreatedThrowsExceptionTest extends HttpApiTestCase
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

    public function testCreateZoneReturnsNotHttpCreatedThrowsException()
    {
        $createZoneDto = new CreateZoneDto(
            ZoneName::fromString('example.at.'),
            Kind::NATIVE,
            [
                new RecordSet(RecordSetName::fromString('www.example.at.'), Type::A, 1800, [
                        new Record('127.0.0.1'),
                        new Record('127.0.0.1'),
                    ]
                ),
            ]
        );

        $this->expectException(UnprocessableEntityException::class);

        $this->createZone->__invoke($createZoneDto);
    }

    private function cleanUp()
    {
        try {
            $this->deleteZone->__invoke(ZoneId::fromString('example.at.'));
        } catch (\Throwable) {
        }
    }
}
