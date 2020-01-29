<?php

namespace DivineOmega\LaravelAddresses\Models;

use DivineOmega\Countries\Countries;
use DivineOmega\Countries\Country;
use DivineOmega\LaravelAddresses\Exceptions\InvalidCountryException;
use DivineOmega\LaravelAddresses\Exceptions\InvalidUKPostcodeException;
use DivineOmega\Postcodes\Utils\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use LangleyFoxall\SimpleGoogleMaps\Factories\SimpleGoogleMapsFactory;

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
        return (new Countries())->getByIsoCode($this->country_code);
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
        if (!$this->country) {
            throw new InvalidCountryException();
        }

        switch ($this->country->isoCodeAlpha3) {
            case 'GBR':
                if (!Validator::validatePostcode($this->postcode)) {
                    throw new InvalidUKPostcodeException();
                }
                break;
        }
    }

    public function geocode() {
        $simpleGoogleMaps = SimpleGoogleMapsFactory::getByKey(config('address.geocoding.google-maps.api-key'));

        $latLng = $simpleGoogleMaps->geocode($this->human_readable);

        $this->latitude = $latLng->lat;
        $this->longitude = $latLng->long;
    }
}