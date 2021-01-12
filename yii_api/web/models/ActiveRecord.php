<?php
namespace app\models;
use app\components\ArrayHelper;
class ActiveRecord extends \yii\db\ActiveRecord {
	public function behaviors()
    {
        return [
             [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
    public static function listData()
    {
        $models = static::find()->where(['status' => 1])->all();
         
        return ArrayHelper::map($models,'id','title');
    }
	
}