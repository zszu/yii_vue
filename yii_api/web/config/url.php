<?php

return [
    'gii' => 'gii',
    'gii/<controller:\w+>' => 'gii/<controller>',
    'gii/<controller:\w+>/<action:\w+>' => 'gii/<controller>/<action>',
    'admin' => 'admin/index',
    'admin/<action:\w+>/<page:\d+>' => 'admin/<action>',
    'admin/<action:\w+>/<op:(c|u|r|d|db|i|p|v)>/<id:\w+>' => 'admin/<action>',
    'admin/<action:\w+>/<op:(c|r)>' => 'admin/<action>',
    'admin/<action:\w+>' => 'admin/<action>',

    //    api
    'api/<controller:\w+>' => 'api/<controller>',
    'api/<controller:\w+>/<action:\w+>' => 'api/<controller>/<action>',


    '' => 'site/index',

    'news/<id:\d+>' => 'site/news-detail',
//
//    [
//        'pattern' => '<action:[^\/]+>/<type:\w+>/page-<page:\d+>',
//        'route' => 'site/<action>',
//        'suffix' => '/',
//    ],
//    [
//        'pattern' => 'work/<type:\w+>',
//        'route' => 'site/work',
//        'suffix' => '/',
//    ],
//    [
//        'pattern' => '<action:[^\/]+>/page-<page:\d+>',
//        'route' => 'site/<action>',
//        'suffix' => '/',
//    ],
//
//    [
//        'pattern' => 'tags/<tag:\w+>',
//        'route' => 'site/tags',
//        'suffix' => '/',
//    ],
//
//
//    [
//        'pattern' => '<action:[^\/]+>',
//        'route' => 'site/<action>',
//        'suffix' => '/',
//    ],


];