<?php

declare(strict_types=1);

namespace Jolicht\Powerdns\Tests\Unit\Model;

use Jolicht\Powerdns\Model\Record;
use Jolicht\Powerdns\Model\RecordSet;
use Jolicht\Powerdns\ValueObject\RecordSetName;
use Jolicht\Powerdns\ValueObject\Type;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jolicht\Powerdns\Model\RecordSet
 */
class RecordSetTest extends TestCase
{
    private RecordSetName $recordSetName;
    private Type $type;
    private array $records;
    private RecordSet $recordSet;

    protected function setUp(): void
    {
        $this->recordSetName = RecordSetName::fromString('www.test.at.');
        $this->type = Type::A;
        $this->records = [
            new Record('127.0.0.1'),
            new Record('127.0.0.2', true),
        ];
        $this->recordSet = new RecordSet($this->recordSetName, $this->type, 1800, $this->records);
    }

    public function testGetName(): void
    {
        $this->assertSame($this->recordSetName, $this->recordSet->getName());
    }

    public function testGetType(): void
    {
        $this->assertSame($this->type, $this->recordSet->getType());
    }

    public function testGetTimeToLive(): void
    {
        $this->assertSame(1800, $this->recordSet->getTimeToLive());
    }

    public function testGetRecords(): void
    {
        $this->assertSame($this->records, $this->recordSet->getRecords());
    }

    public function testFromArray(): void
    {
        $data = [
            'name' => 'www.test.at.',
            'type' => 'A',
            'ttl' => 1800,
            'records' => [
                [
                    'content' => '127.0.0.1',
                    'disabled' => false,
                ],
                [
                    'content' => '127.0.0.2',
                    'disabled' => true,
                ],
            ],
        ];
        $this->assertEquals($this->recordSet, RecordSet::fromArray($data));
    }

    public function testJsonSerialize(): void
    {
        $expected = [
            'name' => 'www.test.at.',
            'type' => 'A',
            'ttl' => 1800,
            'records' => [
                [
                    'content' => '127.0.0.1',
                    'disabled' => false,
                ],
                [
                    'content' => '127.0.0.2',
                    'disabled' => true,
                ],
            ],
        ];

        $this->assertSame($expected, $this->recordSet->jsonSerialize());
    }
}
