<?php

namespace DivineOmega\LaravelAddresses\Objects;

use Exception;
use DivineOmega\Distance\Point;
use DivineOmega\LaravelAddresses\Models\Address;

class Location
{
    private $lat;
    private $lng;

    public function __construct(Address $address)
    {
        if (!$address->isGeocoded()) {
            $address->geocode();
        }

        if (!$address->isGeocoded()) {
            throw new Exception('Failed to geocode the Address to create a Location.');
        }

        $this->lat = $address->latitude;
        $this->lng = $address->longitude;
    }

    public function __toString(): string
    {
        return $this->lat.', '.$this->lng;
    }

    public function toPoint(): Point
    {
        return new Point($this->lat, $this->lng);
    }
}