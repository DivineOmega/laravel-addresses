<?php

namespace DivineOmega\LaravelAddresses\DistanceStrategies;

use DivineOmega\Distance\Distance;
use DivineOmega\Distance\Point;
use DivineOmega\Distance\Types\Haversine;
use DivineOmega\LaravelAddresses\Interfaces\DistanceStrategyInterface;
use DivineOmega\LaravelAddresses\Models\Address;
use DivineOmega\LaravelAddresses\Objects\Location;

class Direct implements DistanceStrategyInterface
{
    public function getDistance(Location $from, Location $to): float
    {
        return (new Distance())
            ->type(new Haversine())
            ->from($from->toPoint())
            ->to($to->toPoint())
            ->get();
    }
}