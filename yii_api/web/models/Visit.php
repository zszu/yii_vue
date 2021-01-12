<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%visit}}".
 *
 * @property integer $id
 * @property string $ip
 * @property string $action
 * @property string $query_string
 * @property string $post_data
 * @property string $day
 * @property integer $created_at
 */
class Visit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%visit}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_data'], 'string'],
            [['day'], 'safe'],
            [['created_at'], 'integer'],
            [['ip'], 'string', 'max' => 15],
            [['action'], 'string', 'max' => 50],
            [['query_string'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'Ip',
            'action' => 'Action',
            'query_string' => 'Query String',
            'post_data' => 'Post Data',
            'day' => 'Day',
            'created_at' => 'Created At',
        ];
    }

    public function behaviors()
    {
        return [
            ['class' => 'yii\behaviors\TimestampBehavior', 'updatedAtAttribute' => false],
        ];
    }

    public static function log($action)
    {
        $model = new static();
        $model->setAttributes([
            'ip' => Yii::$app->request->userIP,
            'action' => $action,
            'query_string' => Yii::$app->request->queryString,
            'post_data' => Json::encode(Yii::$app->request->post()),
            'day' => date('Y-m-d'),
        ]);
        if($action != "stop"){
            $model->save();
        }
    }

    public static function getTotalPV()
    {
        return static::find()->count();
    }

    public static function getTotalUV()
    {
        return static::find()->select('ip')->groupBy('ip, day')->count();
    }

    public static function getPVByDate($date)
    {
        return static::find()->where(['day' => date('Y-m-d', strtotime($date))])->count();
    }

    public static function getUVByDate($date)
    {
        return static::find()->select('ip')->where(['day' => date('Y-m-d', strtotime($date))])->groupBy('ip')->count();
    }

    public static function getPVByMonth($month, $sum = true)
    {
        $start = date('Y-m-d', strtotime($month. '-01'));
        $end = date('Y-m-t', strtotime($start));
        return static::getPVByRange($start, $end, $sum);
    }

    public static function getUVByMonth($month, $sum = true)
    {
        $start = date('Y-m-d', strtotime($month. '-01'));
        $end = date('Y-m-t', strtotime($start));
        return static::getUVByRange($start, $end, $sum);
    }

    public static function getPVByRange($start, $end, $sum = true)
    {
        if ($sum) {
            return static::find()->where(['between', 'day', $start, $end])->count();
        } else {
            $models = static::find()->select('day, count(day) count')->where(['between', 'day', $start, $end])->groupBy('day')->orderBy('day')->asArray()->all();
            return ArrayHelper::map($models, 'day', 'count');
        }

    }

    public static function getUVByRange($start, $end, $sum = true)
    {
        if ($sum) {
            return static::find()->select('ip')->where(['between', 'day', $start, $end])->groupBy('ip')->count();
        } else {
            $models = static::find()->select('day, ip, count(day) count')->where(['between', 'day', $start, $end])->groupBy('day, ip')->orderBy('day')->asArray()->all();
            $return = [];
            foreach($models as $model) {
                $return[$model['day']] = ArrayHelper::getValue($return, $model['day'], 0) + 1;
            }
            return $return;
        }
    }
    //获取一个小时的访问
    public static function getAccess($startTime,$endTime){
        // static $count = 0;
        $AccessArr  = [];
        $pvCount = self::find()->select('ip')->where(['between','created_at',$startTime,$endTime])->count();  //统计一个小时内访问次数
        $ipCount = self::find()->select('ip')->distinct()->where(['between','created_at',$startTime,$endTime])->groupBy('ip')->count();//统计一个小时内ip数
        // var_dump(date('Y-m-d H:i:s',1566894005));die;
        // $count += $ipCount;
        $AccessArr[date('H:i:s',$endTime)] = ['ip'=>$ipCount,'pv'=>$pvCount];
        // var_dump($AccessArr);die;
        return $AccessArr;
    }
    //获取一天的访问
    public static function getOneDayAccess(){
        $OneDayAccessArr = [];
        $startTime = strtotime('today');  //零点
        // var_dump(strtotime(date('2019-8-27 17:00:00')));
        // var_dump(date('Y-m-d H:i:s',$startTime));
        // die;
        // $endTime = strtotime('today')+3600; //凌晨一点
        $cuurentTime = date('H',time());
        if($cuurentTime<1){
            $cuurentTime = 1;
        }
        for($i=1;$i<=$cuurentTime;$i++){
            $AccessArr = self::getAccess($startTime+3600*($i-1),$startTime+3600*$i);
            $getOneDayAccessArr[date('H:i:s',$startTime+3600*$i)] =  $AccessArr[date('H:i:s',$startTime+3600*$i)];
            // var_dump($getOneDayAccessArr);die;
        }
        // var_dump($getOneDayAccessArr);die;
        return $getOneDayAccessArr;
    }

}
