<?php
namespace app\modules\api\v1\models;

use app\common\models\auth\AuthAssignment;
use app\common\models\auth\AuthRole;
use app\helpers\ArrayHelper;
use app\models\UserBaseObj;
use Yii;

class UserObj extends UserBaseObj
{
    //角色 ID
    public $role;
    public function rules()
    {
        return [
            [['username' , 'avatar'] , 'string'],
        ];

    }
    public function fields()
    {
        return [
            'id',
            'username',
            'last_login_at',
            'avatar',
            'status'=>function($model){
                return Yii::$app->params['list']['qy_ty'][$model->status];
            },
            'created_at'=>function($model){
                return date("Y-m-d H:i:s" , $model->created_at);
            },
            'updated_at'=>function($model){
                return date("Y-m-d H:i:s" , $model->updated_at);
            },
            'role' => function($model){
                $assignment = AuthAssignment::findOne(['user_id' => $model->id]);
                return $assignment ? $assignment->role->id : '';
            },

        ];
    }


    /**
     * 返回  所有 用户 和 角色
     */
    public static function getUsersRoles(){
        $users = static::find()
            ->where(['status' => 1])
            ->orderBy('id desc , created_at desc')
            ->all();

        $roles = AuthRole::find()
            ->where(['status' => 1])
            ->orderBy('order_by desc , created_at desc')
            ->asArray()
            ->all();
        $roles = ArrayHelper::map($roles , 'id' , 'title');
        return ['users' => $users , 'roles' => $roles];
    }

    /**
     * @param $model 当前用户 对象
     * 更新 用户 信息
     */
    public static function updateUser($model){
        if (!$model->save()) {
            return false;
        }else{
            //删除 已有 的 角色
            AuthAssignment::deleteAll(['user_id' => $model->id]);
            $rows = [
                'role_id' => $model->role,
                'user_id' => $model->id
            ];
            return Yii::$app->db->createCommand()->insert(AuthAssignment::tableName(), $rows)->execute();
        }
    }

}
