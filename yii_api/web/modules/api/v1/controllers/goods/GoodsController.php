<?php

namespace app\modules\api\v1\controllers\goods;

use app\common\controllers\OnAuthController;
use app\common\models\Goods;

/**
 * Goods controller for the `Module` module
 */
class GoodsController extends OnAuthController
{
    public $modelClass = Goods::class;
    public $authOptional = ['index'];

}
