<?php
namespace app\helpers;

class ArrayHelper extends \yii\helpers\ArrayHelper
{

    /**
     * 递归数组   孩子 节点 放 父类 的 children 中
     *
     * @param array $data
     * @param int $pid
     * @param int $level
     * @param string $pidField
     * @return array
     */
    public static function recursionDataChildren(array $data , $pid = 0 ,$level = 1 ,  $pidField = 'pid'){
        $res = [];

        foreach ($data as $k => $v) {
            if ($v[$pidField] == $pid) {
                $v['children'] =  self::recursionDataChildren($data, $v['id'], $level + 1 , $pidField);
                $v['level'] = $level;
                $res[] = $v;
            }
        }
        return $res;
    }

    /**
     * 递归数组
     *
     * @param array $data
     * @param int $pid
     * @param int $level
     * @param string $pidField
     * @return array
     */
    public static function recursionData(array $data , $pid = 0 ,$level = 1 ,  $pidField = 'pid'){
        static $res = [];

        foreach ($data as $k => $v) {
            if ($v[$pidField] == $pid) {
                $v['level'] = $level;
                $res[] = $v;
                unset($data[$k]);

                self::recursionData($data, $v['id'], $level + 1 , $pidField);
            }
        }
        return $res;
    }

    /**
     *
     * 根据 级别 和 数组 返回字符串
     * @param $level
     * @param array $models
     * @param $k
     * @param int $treeStat
     */
    public static  function itemsLevel($level , array $models , $k , $treeStat = 1){
        $str = '';
        for ($i = 1; $i < $level; $i++) {
            $str .= '　　';

            if ($i == $level - $treeStat) {
                if (isset($models[$k + 1])) {
                    return $str . "├──";
                }

                return $str . "└──";
            }
        }
    }
    /**
     * 递归数组
     *
     * @param array $items
     * @param string $idField
     * @param int $pid
     * @param string $pidField
     * @return array
     */
    public static function itemsMerge(array $items, $pid = 0, $idField = "id", $pidField = 'pid', $child = 'children')
    {
        $arr = [];
        foreach ($items as $v) {
            if ($v[$pidField] == $pid) {
                $v[$child] = self::itemsMerge($items, $v[$idField], $idField, $pidField);
                $arr[] = $v;
            }
        }

        return $arr;
    }

    /**
     * 必须经过递归才能进行重组为下拉框
     *
     * @param $models
     * @param string $idField
     * @param string $titleField
     * @param int $treeStat
     * @return array
     */
    public static function itemsMergeDropDown($models, $idField = 'id', $titleField = 'title',  $treeStat = 1 , $child = 'children')
    {
        $arr = [];
        foreach ($models as $k => $model) {
            $arr[] = [
                $idField => $model[$idField],
                $titleField => self::itemsLevel($model['level'], $models, $k, $treeStat) . " " . $model[$titleField],
            ];

            if (!empty($model[$child])) {
                $arr = ArrayHelper::merge($arr,
                    self::itemsMergeDropDown($model['children'], $idField, $titleField, $treeStat , $child));
            }
        }
        return $arr;
    }

    /**
     * 必须经过递归才能进行重组为 树形
     *
     * @param $models
     * @param string $idField
     * @param string $titleField
     * @param int $treeStat
     * @return array
     */
    public static function itemsTree($models, $idField = 'id', $titleField = 'title', $summaryField = 'summary', $treeStat = 1 , $child = 'children')
    {
        $arr = [];
        foreach ($models as $k => $model) {
            $arr[] = [
                $idField => $model[$idField],
                $titleField => self::itemsLevel($model['level'], $models, $k, $treeStat) . " " . $model[$titleField],
                $summaryField => $model[$summaryField],
            ];

            if (!empty($model[$child])) {
                $arr = ArrayHelper::merge($arr,
                    self::itemsMergeDropDown($model['children'], $idField, $titleField, $treeStat , $child));
            }
        }
        return $arr;
    }

}