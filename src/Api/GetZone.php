<?php

declare(strict_types=1);

namespace Jolicht\PowerdnsBundle\Api;

use Jolicht\PowerdnsBundle\Model\Zone;
use Jolicht\PowerdnsBundle\ValueObject\ZoneId;

interface GetZone
{
    public function __invoke(ZoneId $id): Zone;
}
