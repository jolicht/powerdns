<?php

declare(strict_types=1);

namespace Jolicht\PowerdnsBundle\Model;

use function array_map;

use Jolicht\PowerdnsBundle\ValueObject\Kind;
use Jolicht\PowerdnsBundle\ValueObject\Nsec3Param;
use Jolicht\PowerdnsBundle\ValueObject\ZoneId;
use Jolicht\PowerdnsBundle\ValueObject\ZoneName;
use Webmozart\Assert\Assert;

final class Zone
{
    /**
     * @param RecordSet[] $recordSets
     */
    public function __construct(
        private readonly ZoneId $id,
        private readonly ZoneName $name,
        private readonly Kind $kind,
        private readonly int $serial,
        private readonly string $url,
        private readonly bool $dnssecSigned,
        private readonly ?Nsec3Param $nsec3Param = null,
        private readonly array $recordSets = []
    ) {
        Assert::allIsInstanceOf($this->recordSets, RecordSet::class);
    }

    public function getId(): ?ZoneId
    {
        return $this->id;
    }

    public function getName(): ZoneName
    {
        return $this->name;
    }

    public function getKind(): ?Kind
    {
        return $this->kind;
    }

    public function getSerial(): int
    {
        return $this->serial;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function isDnssecSigned(): bool
    {
        return $this->dnssecSigned;
    }

    public function getNsec3Param(): ?Nsec3Param
    {
        return $this->nsec3Param;
    }

    /**
     * @return RecordSet[]
     */
    public function getRecordSets(): array
    {
        return $this->recordSets;
    }

    public static function fromArray(array $data): self
    {
        Assert::isArray($data['rrsets']);

        return new self(
            id: ZoneId::fromString((string) $data['id']),
            name: ZoneName::fromString((string) $data['name']),
            kind: Kind::from((string) $data['kind']),
            serial: (int) $data['serial'],
            url: (string) $data['url'],
            dnssecSigned: (bool) $data['dnssec'],
            nsec3Param: null !== $data['nsec3param'] ? Nsec3Param::fromString((string) $data['nsec3param']) : null,
            recordSets: array_map(function (array $recordSet) {
                return RecordSet::fromArray($recordSet);
            }, $data['rrsets'])
        );
    }
}
