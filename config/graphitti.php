<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Graphite Host Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the hosts below you wish
    | to use as your default host for all graphite work.
    | Of course you may use many hosts at once using
    | the Graphitti library.
    |
    */

    'default' => env('GRAPHITE_HOST', 'graphite'),

    /*
    |--------------------------------------------------------------------------
    | Graphite Hosts
    |--------------------------------------------------------------------------
    |
    | Here are each of the hosts setup for your application.
    |
    */

    'hosts' => [
        'graphite' => env('GRAPHITE_URL', 'https://graphite.example.com'),
    ],
];
