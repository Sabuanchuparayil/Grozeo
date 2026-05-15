<?php

return [

    'default' => 'razorpay',
    'web_redirect_url' =>  env('WEB_REDIRECT_URL','http://localhost:4200/#/orderComplete-payment/success'),

    'paytm' => [

        /*
    |--------------------------------------------------------------------------
    | Payment Gateway Callback Url
    |--------------------------------------------------------------------------
    | Here you can set the callback url which is used by the payment gateway 
    | when transaction completes.
    |
    */
        'callback_url' => env('PAYTM_CALLBACK_URL', 'https://securegw-stage.paytm.in/theia/paytmCallback'),

        /*
    |--------------------------------------------------------------------------
    | Merchant Key
    |--------------------------------------------------------------------------
    |
    | Here you may specify the merchant key used to authenticate the payment gateway
    |
    */

        'merchant_key' => env('PAYTM_MERCHANT_KEY', ''),

        /*
    |--------------------------------------------------------------------------
    | Merchant MID
    |--------------------------------------------------------------------------
    |
    | Here you may specify the merchant mid for the payment gateway
    |
    */
        'merchant_mid' => env('PAYTM_MERCHANT_ID', ''),

        /**
         *  Class associated with the paytm payment Gateway
         */
        'class' => \App\PaymentGateways\Paytm::class,

    ],

    'instamojo' => [

        'api_key' => env('IM_API_KEY', ''),

        'auth_token' => env('IM_AUTH_TOKEN', ''),

        'url' => env('IM_URL', 'https://test.instamojo.com/api/1.1/'),

        'class' => \App\PaymentGateways\InstamojoPayment::class,
    ],

    'easypay' => [

        'cid' => env('EASYPAY_CID', '6123'),

        'typ' => env('EASYPAY_TYP', 'TEST'),

        'ver' => env('EASYPAY_VER', '1.0'),

        'cny' => env('EASYPAY_CNY', 'INR'),

        're1' => env('EASYPAY_RE1', 'MN'),

        'paymenturl' => env('EASYPAY_PAYMENT_URL', 'https://uat-etendering.axisbank.co.in/easypay2.0/frontend/api/payment'),

        'tokenurl' => env('EASYPAY_TOKEN_URL', 'https://uat-etendering.axisbank.co.in/easypay2.0/frontend/api/generatetoken'),

        'enquiryurl' => env('EASYPAY_ENQUIRY_URL', 'https://uat-etendering.axisbank.co.in/easypay2.0/frontend/index.php/api/enquiry'),

        'checksumkey' => env('EASYPAY_CHECKSUM_KEY', 'axis'),       
        
        'encryptionkey' => env('EASYPAY_ENCRYPTION_KEY', 'axisbank12345678'),  

        'class' => \App\PaymentGateways\EasypayPayment::class,
    ],
    

    'atom' => [

        'login' => env('ATOM_LOGIN', ''),

        'pass' => env('ATOM_PASS', ''),

        'ttype' => env('ATOM_TTYPE', ''),

        'txncurr' => env('ATOM_TXNCURR', 'INR'),

        'clientcode' => env('ATOM_CLIENTCODE', ''),

        'custacc' => env('ATOM_CUSTACC', ''),

        'reqhashkey' => env('ATOM_REQHASHKEY', ''),

        'resphashkey' => env('ATOM_RESPHASHKEY', ''),

        'aesreqhashkey' => env('ATOM_AES_REQHASHKEY', ''),

        'aesreqhashkeysalt' => env('ATOM_AES_REQHASHKEYSALT', ''),

        'aesresphashkey' => env('ATOM_AES_RESPHASHKEY', ''),

        'aesresphashkeysalt' => env('ATOM_AES_RESPHASHKEYSALT', ''),

        'paymenturl' => env('ATOM_PAYMENT_URL', ''),

        'tokenurl' => env('ATOM_TOKEN_URL', ''),

        'enquiryurl' => env('ATOM_ENQUIRY_URL', ''),

        'checksumkey' => env('ATOM_CHECKSUM_KEY', ''),

        'encryptionkey' => env('ATOM_ENCRYPTION_KEY', ''),

        'class' => \App\PaymentGateways\AtomPayment::class,
    ],

    'razorpay' => [

        'key_id' => env('RP_API_KEY_ID', ''),

        'key_secret' => env('RP_API_KEY', ''),

        'cny' => env('RP_CNY', 'INR'),

        'url' => env('IM_URL', ''),

        'class' => \App\PaymentGateways\RazorPayment::class,
    ],
];
