<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class MultipleSelectAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js/admin/multiple-select/multiple-select.css',
    ];
    public $js = [
        'js/admin/multiple-select/multiple-select.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
