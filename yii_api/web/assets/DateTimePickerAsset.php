<?php

namespace app\assets;

use yii\web\AssetBundle;

class DateTimePickerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/datetimepicker/jquery.datetimepicker.full.min.js',
    ];

    public $css = [
        'js/datetimepicker/jquery.datetimepicker.min.css',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
