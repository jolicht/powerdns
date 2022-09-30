<?php

declare(strict_types=1);

namespace Jolicht\Powerdns\Api;

use Jolicht\Powerdns\Dto\CreateZoneDto;
use Jolicht\Powerdns\Model\Zone;

interface CreateZone
{
    public function __invoke(CreateZoneDto $createZoneDto): Zone;
}
