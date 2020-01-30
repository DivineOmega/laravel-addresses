<?php

namespace DivineOmega\LaravelAddresses\Interfaces;

use DivineOmega\LaravelAddresses\Models\Address;

interface DistanceStrategyInterface
{
    public function getDistance(Address $from, Address $to): float;
}