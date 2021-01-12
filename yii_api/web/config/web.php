<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$urlRules = require __DIR__ . '/url.php';
$mailer = require __DIR__ . '/mailer.php';
$config = [
    'id' => 'zu',
    'vendorPath' => dirname(__DIR__) . '/../system/vendor',
    'basePath' => dirname(__DIR__),
    'language' => 'zh-CN',

    //中->英
    'sourceLanguage' => 'zh-CN',
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
            'v1' => ['class' => 'app\modules\api\v1\Module'],
    ],
    'components' => [

        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'mxGhoSAypxH-yA0ZWpBmvTsIhNs8fyB2',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'text/json' => 'yii\web\JsonParser',
            ]
        ],
        'response'=>[
            'class'=>'yii\web\Response',
            'as beforeSend'=>'app\behaviors\ResponseBehavior',
        ],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'idParam' => 'zsz_id',
            'identityClass' => 'app\models\UserBaseObj',
            'identityCookie' => [
                'name' => 'zsz_identity',
                'httpOnly' => true,
            ],
            'enableAutoLogin' => true,
            'loginUrl' => ['/admin/login'],
            'on afterLogin' => ['app\models\User', 'afterLogin'],
            'on beforeLogout' => ['app\models\User', 'afterLogout'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' =>$mailer,
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/' . date('Y-m/d') . '.log',
                ],
            ],
        ],
          //语言包设置
        'i18n' => [
            'translations' => [
                  //作用地方
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'common' => 'common.php',
                         'app' => 'app.php',
                    ],
                ]
            ]
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true, //开启 url 美化
            'showScriptName' => false, //是否显示 index.php
            // 严格解析
            // 'enableStrictParsing' => false,
            'suffix' => '',
            'rules' => [
                // [
                //     'class' => 'yii\rest\UrlRule',
                //     'controller' => [
                //         /**
                //          * 默认登录测试控制器(Post)
                //          * http://当前域名/api/v1/site/login
                //          */
                //         // 版本1
                //         'v1/site',
                //     ],

                //     'pluralize' => false, // 是否启用复数形式，注意index的复数indices，开启后不直观

                //     //必须 配置 额外的 方法
                //     'extraPatterns' => [
                //          // 查询
                //         'GET version' => 'version',
                //         'GET test' => 'test', 
                //     ]
                // ],
            ],
        ],

        //rbac
        'authManager' => [
            'class' =>'yii\rbac\DbManager',
        ],
   
    ],

    //跨域支持
//    'as cors' => [
//        'class' => \yii\filters\Cors::className(),
//        'cors' => [
//            'Origin' => ['*'],
//            'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
//            'Access-Control-Request-Headers' => ['*'],
//            'Access-Control-Allow-Credentials' => true,
//
//        ],
//    ],

    'params' => $params,
];
if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
         'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1' , '::1'],
    ];
}
return $config;
