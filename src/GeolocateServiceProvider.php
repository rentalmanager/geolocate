<?php
namespace RentalManager\Geolocate;

use Illuminate\Support\ServiceProvider;

/**
 * Created by PhpStorm.
 * User: gorankrgovic
 * Date: 9/10/18
 * Time: 5:46 AM
 */

class GeolocateServiceProvider extends ServiceProvider
{


    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Merge config file for the current app
        $this->mergeConfigFrom(__DIR__.'/../config/geolocate.php', 'geolocate');

        // Publish the config files
        $this->publishes([
            __DIR__.'/../config/geolocate.php' => config_path('geolocate.php')
        ], 'geolocate');
    }


    /**
     * Register the app
     */
    public function register()
    {
        $this->app->bind('geolocate', function ($app) {
            return new Geolocate($app);
        });

        $this->app->alias('geolocate', 'RentalManager\Geolocate');
    }

}
