<?php

declare(strict_types=1);

namespace Jolicht\PowerdnsBundle\Api;

use Jolicht\PowerdnsBundle\Dto\CreateZoneDto;
use Jolicht\PowerdnsBundle\Model\Zone;

interface CreateZone
{
    public function __invoke(CreateZoneDto $createZoneDto): Zone;
}
