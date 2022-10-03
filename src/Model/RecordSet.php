<?php

declare(strict_types=1);

namespace Jolicht\PowerdnsBundle\Model;

use function array_map;

use Jolicht\PowerdnsBundle\ValueObject\RecordSetName;
use Jolicht\PowerdnsBundle\ValueObject\Type;
use JsonSerializable;
use Webmozart\Assert\Assert;

final class RecordSet implements JsonSerializable
{
    /**
     * @param Record[] $records
     */
    public function __construct(
        private readonly RecordSetName $name,
        private readonly Type $type,
        private readonly int $timeToLive,
        private readonly array $records
    ) {
    }

    public function getName(): RecordSetName
    {
        return $this->name;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getTimeToLive(): int
    {
        return $this->timeToLive;
    }

    /**
     * @return Record[]
     */
    public function getRecords(): array
    {
        return $this->records;
    }

    public static function fromArray(array $data): self
    {
        Assert::isArray($data['records']);

        return new self(
            name: RecordSetName::fromString((string) $data['name']),
            type: Type::from((string) $data['type']),
            timeToLive: (int) $data['ttl'],
            records: array_map(function (array $record) {
                return Record::fromArray($record);
            }, $data['records'])
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name->toString(),
            'type' => $this->type->value,
            'ttl' => $this->timeToLive,
            'records' => array_map(function (Record $record) {
                return $record->jsonSerialize();
            }, $this->records),
        ];
    }
}
