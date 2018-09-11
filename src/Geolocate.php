<?php
namespace RentalManager\Geolocate;

use Illuminate\Support\Facades\Config;
use InvalidArgumentException;

/**
 * Created by PhpStorm.
 * User: gorankrgovic
 * Date: 9/10/18
 * Time: 5:48 AM
 */

class Geolocate
{
    /**
     * Laravel application.
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;


    /**
     * Geolocate constructor.
     *
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }



    /**
     * @param $url
     * @return mixed
     */
    private function getCurlResponse($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
        //Tell cURL that it should only spend 3 seconds
        //trying to connect to the URL in question.
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, Config::get('geolocate.curl_settings.connecttimeout'));
        //A given cURL operation should only take
        //30 seconds max.
        curl_setopt($ch, CURLOPT_TIMEOUT, Config::get('geolocate.curl_settings.timeout'));
        $response = curl_exec($ch);

        if ( curl_errno($ch) )
        {
            return false;
            curl_close($ch);
        } else {
            curl_close($ch);
            return json_decode($response);
        }
    }


    /**
     * Geolocate the address
     *
     * @param $address
     * @return \StdClass
     */
    public function find($address = null )
    {

        if ( !$address )
        {
            throw new InvalidArgumentException('You must provide an address', 401);
        }
        // url encode the address
        $address = urlencode($address);

        // prepare the url
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $address  . '&key=' . Config::get('geolocate.google_maps_api_key');

        $response = $this->getCurlResponse($url);

        if ( !$response )
        {
            throw new InvalidArgumentException('Response timed out', 408);
        }

        // define the output
        $out = new \StdClass;

        $data = $response;

        if ( $data->status !== 'OK' ) {
            throw new InvalidArgumentException('Not found.', 404);
        } else {

            $allowedTypes = Config::get('geolocate.allowed_types');
            $allowedFirstComponentTypes = Config::get('geolocate.allowed_first_component_types');

            // mark the status
            $out->status = false;

            // we always have multiple results with also multiple address components
            foreach ( $data->results as $result )
            {
                if ( !empty ( array_intersect($allowedTypes, $result->types ) ) )
                {
                    // ok we have the allowed item in the array, but what about the component?.
                    // Not the problem, we can see it easily
                    if ( !empty ( array_intersect( $allowedFirstComponentTypes, $result->address_components[0]->types ) ) ) {
                        // booya, we have the kiddo
                        // fill up the data
                        $out->google_place_id = $result->place_id;
                        $out->searchable_name = urldecode($address);
                        $out->display_name = str_replace(', USA', '', $result->formatted_address);
                        $out->street_number = null;
                        $out->street_name = null;
                        $out->neighborhood = null;
                        $out->city = null;
                        $out->state_code = null;
                        $out->county = null;
                        $out->borough = null;
                        $out->postal_code = null;
                        $out->lat = $result->geometry->location->lat;
                        $out->lng = $result->geometry->location->lng;
                        $out->status = 'OK';

                        // in a loop
                        foreach ( $result->address_components as $component )
                        {
                            switch ( $component->types[0] )
                            {
                                case 'street_number':
                                    $out->street_number = $component->long_name;
                                    break;

                                case 'route':
                                    $out->street_name = $component->long_name;
                                    break;

                                case 'neighborhood':
                                    $out->neighborhood = $component->long_name;
                                    break;

                                case 'locality':
                                    $out->city = $component->long_name;
                                    break;

                                case 'administrative_area_level_2':
                                    $out->county = $component->long_name;
                                    break;

                                case 'administrative_area_level_1':
                                    $out->state_code = $component->short_name;
                                    break;

                                case 'postal_code':
                                    $out->postal_code = $component->long_name;
                                    break;

                                case 'administrative_area_level_3':
                                    $out->borough = $component->long_name;
                                    break;
                            }
                        }
                        break;
                    } else {
                        // No we didn't find the fucker
                        continue;
                    }
                } else {
                    // we do not have an allowed array within a type at all
                    $out->status =  false;
                    continue;
                }
            }

            if ( !$out->status ) {
                throw new InvalidArgumentException('Not found.', 404);
            } else {
                return $out;
            }
        }
    }
}
