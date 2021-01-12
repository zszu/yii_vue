<?php

namespace app\common\models;

use app\components\ArrayHelper;
use app\models\ActiveRecord;
use Yii;

/**
 * This is the model class for table "tsj_menu".
 *
 * @property string $id
 * @property int $pid 父id
 * @property string $name 名称
 * @property string $path 路径
 * @property int $order_by 排序
 * @property string $type 1内部 2外部
 * @property string $is_menu 是否为菜单 1是
 * @property string $desc 描述
 * @property string $icon 图标
 * @property int $create_time
 */
class Menu extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'order_by', 'created_at' , 'updated_at'], 'integer'],
            [['name', 'icon'], 'string', 'max' => 32],
            [['path'], 'string', 'max' => 128],
            [['type', 'is_menu'], 'string', 'max' => 1],
            [['desc'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'name' => 'Name',
            'path' => 'Path',
            'sort' => 'Sort',
            'type' => 'Type',
            'is_menu' => 'Is Menu',
            'desc' => 'Desc',
            'icon' => 'Icon',
            'create_time' => 'Create Time',
        ];
    }
    public static function getMenusTreeByUser(){
        $models = self::find()->all();
        $models = ArrayHelper::toArray($models);
        return self::getTree($models);
    }
    public static function getMenusByUser(){
        $models = self::find()->all();
        $models = ArrayHelper::toArray($models);
        return $models;
    }
    public static function getTree($items,$pid='pid')
    {

        $map  = [];
        $tree = [];
        foreach ($items as &$it)
        {
            //数据的ID名生成新的引用索引树
            $map[$it['id']] = &$it;
        }
        foreach ($items as &$it)
        {
            $parent = &$map[$it[$pid]];


            //选中的子菜单，但没有父级菜单
            if($it[$pid]>0 && !$parent){
                $parentMenu = self::getParent($it[$pid]);
                $map[$parentMenu['id']] = $parentMenu;
                $tree[] = &$map[$it[$pid]];
                $parent = &$map[$it[$pid]];
            }

            if($parent) {
                $parent['children'][] = &$it;
            }else{
                $tree[] = &$it;
            }
        }
        return $tree;
    }
    public function getParent(){
        return $this->hasMany(self::class , ['pid' => 'id']);
    }

}
