# Laravel Addresses

Laravel Addresses is a package that lets you associate addresses with your Laravel
Eloquent models.

Features:

* Automatic geocoding of addresses on change, provided by the Google Maps API
* Validation of address details (country, postcode)
* Conversion of ISO country code to country name
* Ability to store meta data about addresses - e.g. `['type' => 'delivery', 'name' => 'home_address']`

## Installation

To install Laravel Addresses, just run the following Composer command.

```bash
composer require divineomega/laravel-addresses
```

## Configuration

Run the following Artisan command to publish the configuration file.

```bash
php artisan vendor:publish --provider="DivineOmega\LaravelAddresses\ServiceProvider" --force
```

This will create the default configuration file at `config/addresses.php`.

Note that by default, you require a Google Maps API key in order to provide
address geocoding and distance calculations. If you do not wish to use geocoding,
this can be disabled in the configuration.

### Strict geocoding

By default, geocoding is configured as "lenient"; if, for example, the name of a real city is given
but the postcode and street address refer to a nonexistent place, it will geocode as the center of
that city.

Set the `geocoding.strict` flag to `true` in the configuration file to instead fail to geocode in
this scenario.

## Usage

Assign the `HasAddresses` trait to the model you wish to have associated addresses.
For example, you could give the default `User` model address, as shown below.

```php
<?php

namespace App;

use DivineOmega\LaravelAddresses\Traits\HasAddresses;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasAddresses;

    /* ... */
}
```

### Retrieve addresses

```php
$addresses = $user->addresses()->get();
```

### Create new address

```php
$user->addresses()->create([
    'line_1' => '10 Downing Street',
//  'line_2' => '',
    'town_city' => 'Westminster',
    'state_county' => 'London',
    'postcode' => 'SW1A 2AA',
    'country_code' => 'GBR',
]);
```

### Geocoding

Geocoding is automatic when an address is created or updated. You can check if 
an address was successfully geocoding using the `isGeocoded` method.

```php
$address = $user->addresses()->first();

if ($address->isGeocoded()) {
    dd([$address->latitude, $address->longitude]);
}
```

You can also manually geocode the address if needed.

```php
$address = $user->addresses()->first();
$address->geocode();
$address->save();
```

Note that geocoding can fail, in which case, you can detect that it failed by checking whether the
address is geocoded after attempting geocoding:

```php
$address->geocode();

if (!$address->isGeocoded()) {
    // Handle geocoding failure here.
}
```

If there was an existing latitude/longitude set and geocoding fails, these are cleared.

```php
$address->geocode(); // Succeeds

// Change the address details here.

$address->geocode(); // Fails

// Latitude and longitude are now null.
```

### Validation

Validation is automatic when an address is created or updated. You can expect an
appropriate exception to be thrown if validation fails.

* `InvalidCountryException` - Provided `country_code` is not a valid ISO 3166-1 alpha-3 country code.
* `InvalidUKPostcodeException` - If the address is within the UK, the provided `postcode` is not a valid UK postcode.

You can also manually validate the address if needed.

```php
$address = $user->addresses()->first();

try {
    $address->validate();
} catch (\DivineOmega\LaravelAddresses\Exceptions\InvalidUKPostcodeException $e) {
    return back()->withErrors(['Invalid UK postcode.']);
}
```

### Distance calculation

The distance between two different addresses can be calculated using the `distanceTo` 
method. 

```php
$address1 = $user->addresses[0];
$address2 = $user->addresses[1];

$distanceKilometres = $address1->distanceTo($address2);
```

By default the direct distance is calculated (as the crow flies). If you want, you can 
specify a different type of distance calculation, such as driving distance.

```php
use \DivineOmega\LaravelAddresses\DistanceStrategies\Driving;
use \DivineOmega\LaravelAddresses\DistanceStrategies\Walking;
use \DivineOmega\LaravelAddresses\DistanceStrategies\Cycling;

$drivingDistanceKm = $address1->distanceTo($address2, Driving::class);
$walkingDistanceKm = $address1->distanceTo($address2, Walking::class);
$cyclingDistanceKm = $address1->distanceTo($address2, Cycling::class);
```

