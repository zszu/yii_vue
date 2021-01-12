<?php

namespace app\modules\api\v1\controllers;

use app\common\controllers\OnAuthController;
use app\common\models\Menu;
use app\helpers\ResultDataHelper;
use app\modules\api\v1\models\forms\LoginForm;
use Yii;

/**
 * Site controller for the `Module` module
 */
class SiteController extends OnAuthController
{
    public  $modelClass = ''; 

    public function actionLogin()
    {
        $model = new LoginForm();
        $model->attributes = Yii::$app->request->post();
        $menus = Menu::getMenusByUser();
        $menus_tree = Menu::getMenusTreeByUser();
        if ($model->validate()) {
            return ['menus' => $menus , 'menus_tree' => $menus_tree , 'userInfo' => $model->getUser()];
        }

        // 返回数据验证失败
        return ResultDataHelper::api(422, $model->getFirstErrors());


    }


}
