<?php

namespace app\modules\api\v1\controllers\cms;

use app\common\controllers\OnAuthController;

use app\modules\api\v1\models\cms\Articles;

/**
 * Test controller for the `Module` module
 */
class ArticlesController extends OnAuthController
{
    public $modelClass = Articles::class;
    public $authOptional = ['index'];


}
