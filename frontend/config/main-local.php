<?php

$config = [
    'components' => [
        // Configuration Request Frontend [Yii2-User] //
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'baseUrl' => '',
            'enableCookieValidation' => true,
            'cookieValidationKey' => '65GgQqufDxt5AG49gSut7UmlqDfl0cy2',
            'csrfParam' => '_frontendCSRF',
            'csrfCookie' => [
                'httpOnly' => true,
                'path' => '/',
            ],
        ],
        // Configuration Session Frontend [Yii2-User] //
        'session' => [
            'name' => 'FRONTENDSESSID',
            'cookieParams' => [
                'path' => '/',
            ],
        ], 
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
