<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'real-guest-survey-1.0-fronend',
    'name'=>'Real Guest User Survey',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        // Configuration Yii2-User Frontend //
        'user' => [
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_backendIdentity',
                'path' => '/admin',
                'httpOnly' => true,
            ],
        ],
        'authClientCollection' => [
            'class'   => \yii\authclient\Collection::className(),
            'clients' => [
                'facebook' => [
                    'class'        => 'dektrium\user\clients\Facebook',
                    'clientId'     => '1069353863142776',
                    'clientSecret' => '2d9b386d8eefaedb51558c8d4f335db4',
                ],
                'google' => [
                    'class'        => 'dektrium\user\clients\Google',
                    'clientId'     => '476623614513-8ms4jfpknddjqfm8l69blsg7bccr646b.apps.googleusercontent.com',
                    'clientSecret' => 'xIESf8um0y4g61OIvyDzbWb8',
                ],
                'twitter' => [
                    'class'          => 'dektrium\user\clients\Twitter',
                    'consumerKey'    => 'FG6dbmNbVtpbFCbhbdxI69bkY',
                    'consumerSecret' => 'xtoUloS8ZG6tGvS6MeArXN48UJh6xVTLa7Dz1KQUDZYSXvupMK',
                ],
            ],
        ],
        /*'user' => [
            'identityCookie' => [
                'name'     => '_frontendIdentity',
                'path'     => '/',
                'httpOnly' => true,
            ],
        ],*/
        /*'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],*/
        'session' => [
            'name' => 'FRONTENDSESSID',
            'cookieParams' => [
                'httpOnly' => true,
                'path'     => '/',
            ],
        ], 
        /*'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],*/
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ],
    ],
    'modules' => [
        // Configuration Yii2-User Frontend //
        'user' => [
            'class' => 'dektrium\user\Module',
            'mailer' => [
                'sender'                => ['chintan@raindropsinfotech.com' => 'Real Guest User Survey'], // or ['no-reply@myhost.com' => 'Sender name']
                'adminEmail'            => 'chintan@raindropsinfotech.com', // or ['no-reply@myhost.com' => 'Sender name']
                'welcomeSubject'        => 'Welcome to Real Guest User Survey',
                'confirmationSubject'   => 'Confirmation',
                'reconfirmationSubject' => 'Email changed confirmation',
                'recoverySubject'       => 'Account Recovery',
            ],
            'enableFlashMessages' => true,
            'enableRegistration' => true,
            'enableUnconfirmedLogin' => false,
            'confirmWithin' => 21600,
            'cost' => 12,
            'enableGeneratingPassword' => false,
            'enableConfirmation' => true,
            'controllerMap' => [
                'clientDashboard' => 'dektrium\user\controllers\ClientDashboardController',
                'businessLocation' => 'dektrium\user\controllers\BusinessLocationController'
            ],
        ],
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['127.0.0.1', '::1', '192.168.1.*'] // adjust this to your needs
        ]
    ],
    'params' => $params,
];
