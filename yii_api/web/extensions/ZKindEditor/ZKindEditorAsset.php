<?php
namespace app\extensions\ZKindEditor;

use yii\web\AssetBundle;

class ZKindEditorAsset extends AssetBundle
{

    public $sourcePath = '@app/extensions/ZKindEditor/dist';
    public $js = [
        //YII_DEBUG ? 'kindeditor.js' : 'kindeditor-min.js',
        'kindeditor-min.js',
    ];
    public $css = [
    	'themes/default/default.css',
    ];
}