<?php

declare(strict_types=1);

namespace Jolicht\PowerdnsBundle\Api;

use Jolicht\PowerdnsBundle\ValueObject\ZoneId;

interface DeleteZone
{
    public function __invoke(ZoneId $zonedId): void;
}
