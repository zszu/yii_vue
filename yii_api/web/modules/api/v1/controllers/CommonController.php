<?php
namespace app\modules\api\v1\controllers;

use app\common\controllers\OnAuthController;
use app\helpers\FileUpload;
use Yii;
/**
 * Class CommonController
 * @package app\modules\api\v1\controllers
 */
class CommonController extends OnAuthController
{
    public $modelClass = '';
    public $authOptional = ['images'];


    public function actionImages(){
        $upload = new FileUpload();

        $res = $upload->upload('file');

        return ['url' => $res];
    }
    public function actionImages2(){
        $data = Yii::$app->request->post('file');

        $extend = Yii::$app->request->post('extend', 'jpg');
        $upload = new FileUpload();
        $res = $upload->saveBase64($data , $extend);
        return ['url' => $res];
    }

}