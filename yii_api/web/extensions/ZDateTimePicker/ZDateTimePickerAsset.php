<?php

namespace app\extensions\ZDateTimePicker;

use yii\web\AssetBundle;

class ZDateTimePickerAsset extends AssetBundle
{
    public $sourcePath = '@app/extensions/ZDateTimePicker/dist';
    public $css = [
    	'css/jquery.datetimepicker.css',
    ];
    public $js = [                    
        'jquery.datetimepicker.full.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}