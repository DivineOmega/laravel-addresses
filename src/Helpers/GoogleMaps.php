<?php

namespace DivineOmega\LaravelAddresses\Helpers;

use Exception;
use LangleyFoxall\SimpleGoogleMaps\Factories\SimpleGoogleMapsFactory;
use LangleyFoxall\SimpleGoogleMaps\Objects\SimpleGoogleMaps;

abstract class GoogleMaps
{
    private static $simpleGoogleMaps = null;

    public static function instance(): SimpleGoogleMaps
    {
        $apiKey = config('addresses.geocoding.google-maps.api-key');

        if (!$apiKey) {
            throw new Exception('No Google Maps API key specified.');
        }

        if (!self::$simpleGoogleMaps) {
            self::$simpleGoogleMaps = SimpleGoogleMapsFactory::getByKey($apiKey);
        }

        return self::$simpleGoogleMaps;
    }
}