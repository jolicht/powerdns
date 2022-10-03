<?php

declare(strict_types=1);

namespace Jolicht\Powerdns\Api;

use Jolicht\Powerdns\ValueObject\ZoneId;

interface DeleteZone
{
    public function __invoke(ZoneId $zonedId): void;
}
