<?php

return [
    'default' => '',

    'shipyaari' => [
        'creator'               => env('SHIPYAARI_CREATOR', ''),
        'avnkey'                => env('SHIPYAARI_AVNKEY', ''),
        'username'              => env('SHIPYAARI_USERNAME', ''),
        'search_partners'       => 'https://seller.shipyaari.com/logistic/webservice/SearchAvailability_new.php',
        'create_consignment'    => 'https://seller.shipyaari.com/logistic/webservice/create_consignment_api.php',
        'cancel_consignment'    => 'https://seller.shipyaari.com/avn_ci/siteadmin/cancel_consignment/',
        'track_current_status'  => 'https://seller.shipyaari.com/avn_ci/siteadmin/track/trackstatus',
        'track_complete_status' => 'https://seller.shipyaari.com/avn_ci/siteadmin/track/trackdetails'
    ],
    'worldoptions' => [
        'Key'               => env('WORLDOPTIONS_KEY', ''),
        'MeterNumber'       => env('WORLDOPTIONS_METER_NUMBER', ''),
        'Password'          => env('WORLDOPTIONS_PASSWORD', ''),
        'rateService'       => 'http://service.worldoptions.co.uk/RateService.svc?wsdl',
        'createShipment'    => 'http://service.worldoptions.co.uk/ShipmentService.svc?wsdl',
        'voidShipment'      => 'http://service.worldoptions.co.uk/VoidService.svc?wsdl',
        'tracking'          => 'http://service.worldoptions.co.uk/TrackingPOD.svc?wsdl'
    ]
];
