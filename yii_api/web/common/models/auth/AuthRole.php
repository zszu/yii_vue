<?php

namespace app\common\models\auth;

use app\helpers\ArrayHelper;
use app\models\ActiveRecord;
use Yii;

/**
 * This is the model class for table "tsj_aut_role".
 *
 * @property string $id ID
 * @property string $title 标题
 * @property int $order_by 排序
 * @property int $pid 父级 ID
 * @property int $level 等级
 * @property int $status 状态 0：未启用 1：已启用
 * @property int $created_at 创建时间
 * @property int $updated_at 编辑时间
 * @property string $summary 描述
 */
class AuthRole extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%aut_role}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_by', 'pid', 'created_at', 'updated_at' , 'level' , 'status'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['summary'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'order_by' => 'Order By',
            'pid' => 'Pid',
            'level' => 'Level',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updatd At',
            'summary' => 'Summary',
        ];
    }
    public static function getTreeList(){
        $models = static::getItems();
        return ArrayHelper::recursionData($models);
    }
    public static function getDropDownList(){
        $models = static::getItems();
        return ArrayHelper::itemsMergeDropDown(ArrayHelper::recursionData($models));
    }

    public static function getItems(){

        $items = self::find()
            ->select(['id' , 'pid' , 'level' , 'order_by' ,'status' , 'title' , 'summary' , 'created_at'])
            ->where(['status' => 1])
            ->orderBy('order_by desc , created_at desc ')
            ->asArray()
            ->all();

        return $items;
    }

}
