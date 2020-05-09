<?php

namespace DivineOmega\LaravelAddresses\Traits;

use DivineOmega\LaravelAddresses\Models\Address;

trait HasAddress
{
    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}