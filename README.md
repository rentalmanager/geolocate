# RentalManager - Geolocate

A package made for Rentbits for easier maintenance and modularity of managing rental listings. 
It includes all migrations, models and relations to run the rental system.

## Installation, Configuration and Usage

### Installation

Via Composer

```bash
composer require rentalmanager/geolocate
```


### Configuration

Once you install the package, it should be automatically discovered by the Laravel. To check this, in your terminal simply run the:


``` bash
$ php artisan
```

There you should find the all `rm:*` commands.

First step after checking is to publish the vendors:

``` bash
$ php artisan vendor:publish --tag="geolocate"
```
You can setup the Google Maps API key in the config file or in the .env file. Other settings are available in the config file as well.

Thats it...

## Usage

This package is used to geolocate the property through the provided Facade

## Facade

This package provides methods for fetching, geolocating records. 
For example you have a following situation:

(First of you need to use the Facade as )

`use RentalManager\Geolocate\Facades\Geolocate;`

in your class.

```php
// You have the address in a string 
$address = '2136 Market St, Philadelphia, PA 19103';

// So you just want to get the geolocation data from it
$response = Geolocate::find($address);
```

Dump the response to see what it retrieves
