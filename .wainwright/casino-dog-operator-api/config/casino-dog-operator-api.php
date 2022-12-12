<?php

// config for Wainwright/CasinoDogOperatorApi
return [

    'access' => [
      'key' => env('WAINWRIGHT_CASINODOG_OPERATOR_KEY', '25ec095c710439e79f7eb254b93d0403'),
      'secret' => env('WAINWRIGHT_CASINODOG_OPERATOR_SECRET', '123456'),
  ],
    'test_settings' => [
        'start_balance' => env('WAINWRIGHT_CASINODOG_OPERATOR_STARTING_BALANCE', 22500), // enter starting balance (integer in cents)
    ],

    // You can customize endpoints as you like, and split/scale however you feel fit. 
    // Please do note if you change a single endpoint to go to different host that the "casino-dog-operator-api:connect-to-api" will not update custom endpoints
    'api_url' => env('WAINWRIGHT_CASINODOG_OPERATOR_API_BASEURL', 'https://casinoman.app'), /* api_url is the base url to contact, it should not end with slash */
    'endpoints' => [
      'create_session' => env('WAINWRIGHT_CASINODOG_OPERATOR_API_CREATESESSION', 'https://casinoman.app/api/createSession'),
      'gameslist' =>  env('WAINWRIGHT_CASINODOG_OPERATOR_API_GAMESLIST', 'https://casinoman.app/api/gameslist/all'),
      'access_ping' => env('WAINWRIGHT_CASINODOG_OPERATOR_API_ACCESSPING', 'https://casinoman.app/api/accessPing'),
    ],
];