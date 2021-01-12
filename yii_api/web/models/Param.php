<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%param}}".
 *
 * @property string $id
 * @property integer $order_by
 * @property integer $type
 * @property string $name
 * @property string $hint
 * @property string $value
 * @property string $input_type
 * @property string $input_list
 * @property integer $width
 * @property integer $status
 * @property integer $updated_at
 */
class Param extends \yii\db\ActiveRecord
{
    const CACHE_KEY = 'model.param';
    const CACHE_TIME = 120;

    const TYPE_SYSTEM = 1;  //系统参数
    const TYPE_SITE = 2;// 站点设置
    const TYPE_COMPANY = 3; //公司设置
    const TYPE_MAIL = 4;  //邮箱设置
    const TYPE_SYSTEM_WH = 6;//尺寸
    public static $typeNames = [
        self::TYPE_SYSTEM => '系统',
        self::TYPE_SITE => '站点',
        self::TYPE_COMPANY => '公司',
        self::TYPE_MAIL => '邮件',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%param}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['order_by', 'type', 'width', 'status', 'updated_at'], 'integer'],
            [['value'], 'string'],
            [['id', 'name'], 'string', 'max' => 50],
            [['hint', 'input_list'], 'string', 'max' => 255],
            [['input_type'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_by' => 'Order By',
            'type' => 'Type',
            'name' => 'Name',
            'hint' => 'Hint',
            'value' => 'Value',
            'input_type' => 'Input Type',
            'input_list' => 'Input List',
            'width' => 'Width',
            'status' => 'Status',
            'updated_at' => 'Updated At',
        ];
    }

    public function behaviors()
    {
        return [
            ['class' => '\yii\behaviors\TimestampBehavior', 'createdAtAttribute' => false],
        ];
    }
    public static function findValue($name){
        return Param::find()->where(['name'=>$name])->one()['value'];
    }

    public function afterSave($insert, $changedAttributes)
    {
        self::deleteCache();
    }

    public static function getValue($name, $defaultValue = null)
    {
        $params = self::listData();
        return ArrayHelper::getValue($params, $name, $defaultValue);
    }

    public static function setValue($id, $value)
    {
        $model = self::findOne(['id' => $id]);
        if ($model) {
            $model->value = strval($value);
            if (!$model->save()) {
                Yii::error($model->errors);
            }
        }
    }

    public static function listData($type = null)
    {
        $models = Yii::$app->cache->get(self::CACHE_KEY);
        if ($models === false) {
            $models = self::find()
                ->select(['name', 'value', 'type'])
                ->where(['status' => 1])
                ->andFilterWhere(['type' => $type])
                ->asArray()
                ->all();
            Yii::$app->cache->set(self::CACHE_KEY, $models, self::CACHE_TIME);
        }
        return ArrayHelper::map(array_filter($models, function($row) use ($type) {
            return empty($type) || $row['type'] == $type;
        }), 'name', 'value');
    }

    public static function deleteCache()
    {
        Yii::$app->cache->delete(self::CACHE_KEY);
    }
}
