<?php
return [
	'id' => 'real-guest-survey-1.0',
    'name'=>'Real Guest Survey',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        // Configuration Urls Managers //
	    /*'urlManager' => [
	    	'class' => 'yii\web\UrlManager',
	    	'enablePrettyUrl' => true,
	    	'suffix'=>'.html',
	    	'showScriptName' => false,
	    ],*/
    ],
    'modules' => [
	    'user' => [
	        'class' => 'dektrium\user\Module',
	        // you will configure your module inside this file
	        // or if need different configuration for frontend and backend you may
	        // configure in needed configs
	    ],
	],
];
