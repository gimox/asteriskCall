<?php

$params = require(__DIR__ . '/params.php');
$url = require(__DIR__ . '/url.php');

$config = [
    'id' => 'foxboxApi',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'nOhpJg7LBv_odlecKbSTz5t3Ncqblcog',
            'enableCsrfValidation' => false,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager'   => [
            'class'               => 'yii\web\UrlManager',
            'enablePrettyUrl'     => true,
            'enableStrictParsing' => false,
            'suffix'              => false,
            'showScriptName'      => false,
            'rules'               => $url

        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
  //  $config['bootstrap'][] = 'debug';
  //  $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
