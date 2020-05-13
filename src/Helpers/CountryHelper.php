<?php


namespace DivineOmega\LaravelAddresses\Helpers;


use DivineOmega\Countries\Countries;
use DivineOmega\Countries\Country;

abstract class CountryHelper
{
    static $countriesByCode = [];

    static function getByIsoCode($countryCode): ?Country
    {
        if (array_key_exists($countryCode, self::$countriesByCode)) {
            return self::$countriesByCode[$countryCode];
        }

        $country = (new Countries())->getByIsoCode($countryCode);

        self::$countriesByCode[$countryCode] = $country;

        return $country;
    }
}
