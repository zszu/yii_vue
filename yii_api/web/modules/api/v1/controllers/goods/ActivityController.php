<?php
namespace app\modules\api\v1\controllers\goods;
use app\common\controllers\OnAuthController;

/**
 * Class ActivityController
 * @package app\modules\api\v1\controllers
 * @desc 商品活动
 */
class ActivityController extends OnAuthController
{
    public $modelClass = '';
    public $authOptional  = [];
}
