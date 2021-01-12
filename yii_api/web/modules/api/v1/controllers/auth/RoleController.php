<?php
namespace app\modules\api\v1\controllers\auth;

use app\common\controllers\OnAuthController;
use app\common\models\auth\AuthItem;
use app\common\models\auth\AuthItemForm;
use app\common\models\auth\AuthItemRole;
use app\common\models\auth\AuthRole;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

/**
 * Class RoleController
 * @package app\modules\api\v1\controllers\auth
 * @desc 角色管理
 */
class RoleController extends OnAuthController
{
    public $modelClass = AuthRole::class;
    public $authOptional = ['index'  , 'promission'];

    public function actionIndex()
    {
        return AuthRole::getTreeList();
    }
    /**
     * @param $id 角色 id
     * @return ActiveDataProvider
     * @desc 用户权限
     */
    public function actionPromission($id){
        return AuthItem::getItemTreeByRoleId($id);
    }

    /**
     * @param $role_id 角色 id
     * @param $authIds 权限 id  数组
     * @return string
     * @desc 修改用户权限
     */
    public function actionPromissionEdit()
    {
        //授权
        $role_id = Yii::$app->request->post('role_id');
        $authIds = Yii::$app->request->post('authIds');

        $authIds = $authIds ? explode(',', $authIds) : [];
        AuthItemRole::deleteAll(['role_id' => $role_id]);

        $rows = [];
        foreach ($authIds as $id) {
            $rows[] = [
                $role_id,
                $id
            ];
        }

        $field = ['role_id', 'item_id'];
        return !empty($rows) && Yii::$app->db->createCommand()->batchInsert(AuthItemRole::tableName(), $field, $rows)->execute();

    }



}