<?php

declare(strict_types=1);

namespace Jolicht\Powerdns\Tests\Unit\Model;

use Jolicht\Powerdns\Model\Record;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jolicht\Powerdns\Model\Record
 */
class RecordTest extends TestCase
{
    private Record $record;

    protected function setUp(): void
    {
        $this->record = new Record('testContent', true);
    }

    public function testGetContent(): void
    {
        $this->assertSame('testContent', $this->record->getContent());
    }

    public function testIsDisabled(): void
    {
        $this->assertTrue($this->record->isDisabled());
    }

    public function testIsDisabledDefaultFalse(): void
    {
        $record = new Record('testContent');
        $this->assertFalse($record->isDisabled());
    }

    public function testJsonSerialize(): void
    {
        $expected = [
            'content' => 'testContent',
            'disabled' => true,
        ];
        $this->assertSame($expected, $this->record->jsonSerialize());
    }

    public function testFromArray(): void
    {
        $data = [
            'content' => 'testContent',
            'disabled' => true,
        ];
        $this->assertEquals($this->record, Record::fromArray($data));
    }
}
