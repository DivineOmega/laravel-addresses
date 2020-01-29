<?php

namespace DivineOmega\LaravelAddresses\Observers;

use DivineOmega\LaravelAddresses\Models\Address;

class AddressObserver
{
    public function saving(Address $address)
    {
        $address->validate();
        $address->geocode();
    }
}