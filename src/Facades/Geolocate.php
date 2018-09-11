<?php
namespace RentalManager\Geolocate\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Created by PhpStorm.
 * Date: 7/3/18
 * Time: 6:42 PM
 * Locations.php
 * @author Goran Krgovic <goran@dashlocal.com>
 */

class Geolocate extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'geolocate';
    }

}
