<?php
namespace app\modules\api\v1\models;

use app\helpers\ArrayHelper;

class Menu extends \app\common\models\Menu
{

    //根据 pid 获取 所有 子菜单
    public static function getChildMenuByPid($pid = 0)
    {
        $where = ['>','id','0'];
        $data = self::getAllMenus($where);
        $childMenu = self::getSubMenu($data,$pid);

        return $childMenu;
    }
    //获取 菜单
    public static function getAllMenus($where,$order='order_by ASC')
    {
        $data = self::find()->where(['status' => 1])->andWhere($where)->orderBy($order)->asArray()->all();
        return $data;
    }
    public static function getSubMenu($menus,$pid = 0,&$list=array(),$level=0)
    {
        foreach($menus as $menu)
        {
            if($menu['pid']==$pid){
                $menu['level'] = $level;
                $list[] = $menu;
                self::getSubMenu($menus,$menu['id'],$list,$level+1);
            }
        }

        return $list;
    }

    //以上 弃用

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
            ->select(['id' , 'pid' , 'name' , 'path'  , 'is_menu' ,'order_by'  , 'icon' , 'status' , 'created_at'])
            ->where(['status' => 1])
            ->orderBy('order_by desc , created_at desc ')
            ->asArray()
            ->all();

        return $items;
    }
}