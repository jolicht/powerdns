<?php

declare(strict_types=1);

namespace Jolicht\Powerdns\Model;

use Jolicht\DogadoFqdn\FullyQualifiedDomainName;
use JsonSerializable;

use function array_map;

final class RecordSet implements JsonSerializable
{
    /**
     * @param Record[] $records
     */
    public function __construct(
        private readonly FullyQualifiedDomainName $name,
        private readonly Type $type,
        private readonly int $timeToLive,
        private readonly array $records
    ) {
    }

    public function getName(): FullyQualifiedDomainName
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

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name->getFullyQualifiedName(),
            'type' => $this->type->value,
            'ttl' => $this->timeToLive,
            'records' => array_map(function (Record $record) {
                return $record->jsonSerialize();
            }, $this->records),
        ];
    }
}
