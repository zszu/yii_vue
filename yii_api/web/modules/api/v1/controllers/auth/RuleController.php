<?php
namespace app\modules\api\v1\controllers\auth;


use app\common\controllers\OnAuthController;
use app\common\models\auth\AuthItem;

/**
 * Class RuleController
 * @package app\modules\api\v1\controllers\auth
 * @desc 规则管理
 */
class RuleController extends  OnAuthController
{
   public $modelClass = AuthItem::class;
   public $authOptional = [];

   public function actionIndex()
   {
       return AuthItem::getTreeList();
   }


}