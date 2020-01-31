<?php

namespace DivineOmega\LaravelAddresses\DistanceStrategies;

use DivineOmega\Distance\Distance;
use DivineOmega\Distance\Point;
use DivineOmega\Distance\Types\Haversine;
use DivineOmega\LaravelAddresses\Interfaces\DistanceStrategyInterface;
use DivineOmega\LaravelAddresses\Models\Address;

class Direct implements DistanceStrategyInterface
{
    public function getDistance(Address $from, Address $to): float
    {
        return (new Distance())
            ->type(new Haversine())
            ->from(new Point((float) $from->latitude, (float) $from->longitude))
            ->to(new Point((float) $to->latitude, (float) $to->longitude))
            ->get();
    }
}