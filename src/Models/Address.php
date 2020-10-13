<?php

namespace DivineOmega\LaravelAddresses\Models;

use DivineOmega\Countries\Countries;
use DivineOmega\Countries\Country;
use DivineOmega\LaravelAddresses\DistanceStrategies\Direct;
use DivineOmega\LaravelAddresses\Exceptions\InvalidCountryException;
use DivineOmega\LaravelAddresses\Exceptions\InvalidUKPostcodeException;
use DivineOmega\LaravelAddresses\Helpers\CountryHelper;
use DivineOmega\LaravelAddresses\Helpers\GoogleMaps;
use DivineOmega\LaravelAddresses\Interfaces\DistanceStrategyInterface;
use DivineOmega\LaravelAddresses\Objects\Location;
use DivineOmega\Postcodes\Utils\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string line_1
 * @property string line_2
 * @property string town_city
 * @property string state_county
 * @property string postcode
 * @property string country_code
 * @property Country country
 * @property string country_name
 * @property float latitude
 * @property float longitude
 * @property string human_readable
 */
class Address extends Model
{
    protected $casts = [
        'meta' => 'array',
    ];

    protected $appends = [
        'country',
        'country_name',
        'human_readable',
    ];

    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getCountryAttribute(): ?Country
    {
        if (!$this->country_code) {
            return null;
        }

        return CountryHelper::getByIsoCode($this->country_code);
    }

    public function getCountryNameAttribute(): ?string
    {
        return $this->country ? $this->country->name : null;
    }

    public function getHumanReadableAttribute(): string
    {
        return collect([
            $this->line_1,
            $this->line_2,
            $this->town_city,
            $this->state_county,
            $this->postcode,
            $this->country_name,
        ])->filter()->implode(', ');
    }

    public function validate(): void
    {
        if (config('addresses.validation.country-code') && !$this->country) {
            throw new InvalidCountryException();
        }

        switch ($this->country->isoCodeAlpha3) {
            case 'GBR':
                if (config('addresses.validation.uk-postcode') && !Validator::validatePostcode($this->postcode)) {
                    throw new InvalidUKPostcodeException();
                }
                break;
        }
    }

    public function geocode(): void
    {
        if (!config('addresses.geocoding.enabled')) {
            return;
        }

        $latLng = GoogleMaps::instance()
            ->allowPartialMatches()
            ->geocode($this->human_readable);

        if ($latLng) {
            $this->latitude = $latLng->lat;
            $this->longitude = $latLng->long;
        } else {
            $this->latitude = null;
            $this->longitude = null;
        }
    }

    public function isGeocoded(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    public function distanceTo(Address $to, DistanceStrategyInterface $distanceStrategy = null): float
    {
        if (!$distanceStrategy) {
            $distanceStrategy = new Direct();
        }

        return $distanceStrategy->getDistance(new Location($this), new Location($to));
    }
}