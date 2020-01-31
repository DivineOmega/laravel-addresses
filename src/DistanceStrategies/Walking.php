<?php

namespace DivineOmega\LaravelAddresses\DistanceStrategies;

use DivineOmega\LaravelAddresses\Helpers\GoogleMaps;
use DivineOmega\LaravelAddresses\Interfaces\DistanceStrategyInterface;
use DivineOmega\LaravelAddresses\Models\Address;
use DivineOmega\LaravelAddresses\Objects\Location;
use LangleyFoxall\SimpleGoogleMaps\Objects\Enums\TravelMode;

class Walking implements DistanceStrategyInterface
{
    public function getDistance(Location $from, Location $to): float
    {
        return GoogleMaps::instance()
            ->directions($from, $to, TravelMode::WALKING)
            ->distance();
    }
}