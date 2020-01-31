<?php

namespace DivineOmega\LaravelAddresses\Interfaces;

use DivineOmega\LaravelAddresses\Objects\Location;

interface DistanceStrategyInterface
{
    public function getDistance(Location $from, Location $to): float;
}