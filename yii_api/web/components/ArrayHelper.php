<?php
namespace app\components;
/**
 * 数组助手
 */
class ArrayHelper extends \yii\helpers\ArrayHelper
{
	
	/**
	  * @param $_data array
	  * @param $_data int
	  * @param $_data int
	  * return Array
	 */
	  //无限级分类
    public static function cateGroy($_data, $_pid = 0, $_level = 1) {
        static $_new_data = [];

        foreach ($_data as $_key => $_value) {
            if ($_value['up_id'] == $_pid) {
                $_value['level'] = $_level;
                $_new_data[] = $_value;
                unset($_data[$_key]);
                self::cateGroy($_data, $_value['id'], $_level + 1);
            }
        }
        return $_new_data;
    }
}