<?php
return [
	'dynamodb' => [
        'credentials' => [
            'key'       => env('AWS_DYNAMODB_ACCESS_KEY_ID', ''),
            'secret'    => env('AWS_DYNAMODB_SECRET_ACCESS_KEY', ''),
        ],
        'region'	=> 'ap-southeast-1',
        // 'endpoint'	=> 'https://streams.dynamodb.ap-southeast-1.amazonaws.com',
        'version'   => '2012-08-10'
    ],
];