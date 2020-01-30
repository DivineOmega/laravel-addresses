<?php

namespace DivineOmega\LaravelAddresses\DistanceStrategies;

use DivineOmega\Distance\Distance;
use DivineOmega\Distance\Point;
use DivineOmega\Distance\Types\Haversine;
use DivineOmega\LaravelAddresses\Interfaces\DistanceStrategyInterface;
use DivineOmega\LaravelAddresses\Models\Address;

class DirectDistanceStrategy implements DistanceStrategyInterface
{
    public function getDistance(Address $from, Address $to): float
    {
        return (new Distance())
            ->type(new Haversine())
            ->from(new Point($from->latitude, $from->longitude))
            ->to(new Point($to->latitude, $to->longitude))
            ->get();
    }
}