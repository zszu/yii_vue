<?php

namespace app\common\models\auth;

use app\helpers\ArrayHelper;
use app\models\ActiveRecord;
use Yii;

/**
 * This is the model class for table "tsj_item".
 *
 * @property string $id
 * @property string $title 标题
 * @property string $name 别名
 * @property int $order_by 排序
 * @property string $type 类别
 * @property int $pid
 * @property int $level 级别
 * @property int $status 状态[;0:未启用;1已启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 编辑时间
 */
class AuthItem extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_by', 'pid', 'level', 'created_at', 'updated_at' , 'status'], 'integer'],
            [['title', 'name'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 20],
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
            'name' => 'Name',
            'order_by' => 'Order By',
            'type' => 'Type',
            'pid' => 'Pid',
            'level' => 'Level',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @param $role_id 角色 ID
     * @return array
     * @desc 根据角色id 返回 当前角色 的 权限
     */
    public static function getItemTreeByRoleId($role_id){
        //当前角色权限
        $curRules = AuthItemRole::find()
            ->where(['status' => 1 , 'role_id' => $role_id])
            ->orderBy('order_by desc , created_at desc')
            ->asArray()
            ->all();

        $curRules = ArrayHelper::map($curRules , 'item_id' , 'item_id');
        //获取所有的权限
        $allRules = self::getAllItemTree();
        //返回所有可显示的权限  和 当前角色所拥有的权限
        return ['all_rule'=>$allRules,'current_role_auth'=>$curRules];
    }

    //树形 数据
    public static function getAllItemTree(){
        $models = static::getItems();
        return ArrayHelper::recursionDataChildren($models);
    }

   //树形 显示
    public static function getTreeList(){
        $models = static::getItems();
        return ArrayHelper::recursionData($models);
    }
    //下拉 显示
    public static function getDropDownList(){
        $models = static::getItems();
        return ArrayHelper::itemsMergeDropDown(ArrayHelper::recursionData($models));
    }

    public static function getItems(){

        $items = self::find()
            ->select(['id' , 'pid' , 'level' , 'order_by' ,'status' , 'title' , 'name' , 'created_at'])
            ->where(['status' => 1])
            ->orderBy('order_by desc , created_at desc ')
            ->asArray()
            ->all();

        return $items;
    }

}
