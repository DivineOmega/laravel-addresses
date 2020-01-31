<?php

namespace DivineOmega\LaravelAddresses\Traits;

use DivineOmega\LaravelAddresses\Models\Address;

trait HasAddresses
{
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }
}