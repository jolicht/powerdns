<?php

declare(strict_types=1);

namespace Jolicht\Powerdns\Model;

use Jolicht\DogadoFqdn\FullyQualifiedDomainName;

final class Zone
{
    public function __construct(
        private readonly ?FullyQualifiedDomainName $id,
        private readonly FullyQualifiedDomainName $name,
        private readonly ?Kind $kind,

    )
    {
    }
}
