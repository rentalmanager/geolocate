<?php
/**
 * Created by PhpStorm.
 * User: gorankrgovic
 * Date: 9/10/18
 * Time: 5:50 AM
 */

return [

    /*
      |--------------------------------------------------------------------------
      | Google maps API key
      |--------------------------------------------------------------------------
      |
      | Used dominantly for this package
      |
      */
    'google_maps_api_key' => env('GOOGLE_MAPS_API_KEY'),

    /*
     * Curlopt settings
     */
    'curl_settings' => [
        'connecttimeout' => 3,
        'timeout' => 10
    ],


    /*
     * Allowed types from Google response
     */
    'allowed_types' => [
        'locality',
        'street_number',
        'street_address',
        'route',
        'premise',
        'subpremise',
        'establishment',
        'post_box',
        'room',
        'postal_code',
        'floor'
    ],


    /*
     * Allowed first component types
     */
    'allowed_first_component_types' => [
        'locality',
        'street_number',
        'street_address',
        'route',
        'premise',
        'subpremise',
        'floor',
        'post_box',
        'postal_code',
        'establishment',
        'room'
    ]
];
