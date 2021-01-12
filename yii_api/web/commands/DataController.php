<?php
namespace app\commands;

use yii\console\Controller;
use Yii;
use yii\console\ExitCode;

/**
 * Class DataController
 * @package app\commands
 * @desc articles 中添加数据
 */
class DataController extends Controller
{
    //表中插入数据
    public function actionInsert($table){
        $field = ['title' , 'subtitle' , 'summary' , 'content'];
        $rows = [];
        for($i = 1 ; $i < 1000 ; $i++){
            $row = ['商品标题测试'.$i , '商品测试副标题' . $i ,'商品测试简介' . $i , '商品测试内容' . $i];
            $rows[] = $row;
        }
        $tableName = 'tsj_' . $table;

        Yii::$app->db->createCommand()->batchInsert($tableName , $field, $rows)->execute();
        echo $tableName. '  ' . '插入' . $i . '条数据成功';
        return ExitCode::OK;
    }

    //截断表
    public function actionTruncate($table){
        $sql = 'truncate table tsj.' . $table;
        Yii::$app->db->createCommand($sql)->execute();
    }

}