<?php
namespace app\modules\api\v1\controllers;

use app\common\controllers\OnAuthController;
use \app\modules\api\v1\models\Menu;
use Yii;

class MenuController extends OnAuthController
{
    public $modelClass = Menu::class;
    public $authOptional = ['index' , 'create' , 'update' , 'delete' , 'view'];
    public function actionIndex(){
        $pid = Yii::$app->request->get('pid');
        return Menu::getTreeList();
    }
}