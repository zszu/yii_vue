<?php

namespace app\common\models\auth;

use app\models\ActiveRecord;
use Yii;

/**
 * This is the model class for table "tsj_auth_item_role".
 *
 * @property int $role_id 角色 ID
 * @property int $item_id 权限ID
 * @property string $name 别名
 * @property string $type 类别
 * @property int $order_by 排序
 * @property int $status 状态 0：未启用 1：已启用
 * @property int $created_at 创建时间
 * @property int $updated_at 编辑时间
 */
class AuthItemRole extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_item_role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'item_id'], 'required'],
            [['role_id', 'item_id', 'order_by', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['type'], 'string', 'max' => 20],
            [['status'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'item_id' => 'Item ID',
            'name' => 'Name',
            'type' => 'Type',
            'order_by' => 'Order By',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
