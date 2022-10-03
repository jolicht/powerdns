<?php

declare(strict_types=1);

namespace Jolicht\PowerdnsBundle\Dto;

use function array_map;

use Jolicht\PowerdnsBundle\Model\RecordSet;
use Jolicht\PowerdnsBundle\ValueObject\Kind;
use Jolicht\PowerdnsBundle\ValueObject\Nameserver;
use Jolicht\PowerdnsBundle\ValueObject\Nsec3Param;
use Jolicht\PowerdnsBundle\ValueObject\ZoneName;
use JsonSerializable;
use Webmozart\Assert\Assert;

final class CreateZoneDto implements JsonSerializable
{
    /**
     * @param RecordSet[] $recordSets
     */
    public function __construct(
        private readonly ZoneName $name,
        private readonly Kind $kind,
        private readonly array $nameservers = [],
        private readonly bool $dnssecSigned = false,
        private readonly ?Nsec3Param $nsec3Param = null,
        private readonly array $recordSets = []
    ) {
        Assert::allIsInstanceOf($this->nameservers, Nameserver::class);
        Assert::allIsInstanceOf($this->recordSets, RecordSet::class);
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name->toString(),
            'kind' => $this->kind->value,
            'dnssec' => $this->dnssecSigned,
            'nsec3param' => null !== $this->nsec3Param ? $this->nsec3Param->toString() : null,
            'nameservers' => array_map(function (Nameserver $nameserver) {
                return $nameserver->toString();
            }, $this->nameservers),
            'rrsets' => array_map(function (RecordSet $recordSet) {
                return $recordSet->jsonSerialize();
            }, $this->recordSets),
        ];
    }
}
