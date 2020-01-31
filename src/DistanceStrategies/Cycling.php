<?php

namespace DivineOmega\LaravelAddresses\DistanceStrategies;

use DivineOmega\LaravelAddresses\Helpers\GoogleMaps;
use DivineOmega\LaravelAddresses\Interfaces\DistanceStrategyInterface;
use DivineOmega\LaravelAddresses\Models\Address;
use LangleyFoxall\SimpleGoogleMaps\Objects\Enums\TravelMode;

class Cycling implements DistanceStrategyInterface
{
    public function getDistance(Address $from, Address $to): float
    {
        return GoogleMaps::instance()
            ->directions($from->human_readable, $to->human_readable, TravelMode::BICYCLING)
            ->distance();
    }
}