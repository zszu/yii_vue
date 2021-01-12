<?php

namespace app\common\models\auth;

use app\models\ActiveRecord;
use Yii;

/**
 * This is the model class for table "tsj_auth_assignment".
 *
 * @property int $role_id
 * @property int $user_id
 */
class AuthAssignment extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_assignment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'user_id'], 'required'],
            [['role_id', 'user_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'user_id' => 'User ID',
        ];
    }
    public function getRole(){
        return $this->hasOne(AuthRole::class , ['id' => 'role_id']);
    }
}
