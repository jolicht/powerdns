<?php

declare(strict_types=1);

namespace Jolicht\Powerdns\Api;

use Jolicht\Powerdns\Model\Zone;
use Jolicht\Powerdns\ValueObject\ZoneId;

interface GetZone
{
    public function __invoke(ZoneId $id): Zone;
}
