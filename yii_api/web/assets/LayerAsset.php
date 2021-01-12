<?php

namespace app\assets;

use yii\web\AssetBundle;

class LayerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/layer/layer.js',
    ];

    /**
     * @param \yii\web\View $view
     */
    public function registerAssetFiles($view)
    {
        parent::registerAssetFiles($view);
        $view->registerJs(<<<JS
window.alert = layer.msg;
window.confirm = layer.confirm;
JS
);
    }
}
