<?php

namespace app\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_log}}".
 *
 * @property string $id
 * @property integer $type
 * @property integer $user_id
 * @property string $content
 * @property string $item_id
 * @property string $created_ip
 * @property integer $created_at
 */
class UserLog extends \yii\db\ActiveRecord
{
    CONST TYPE_LOGIN = 1;

    public static $typeNames = [
        self::TYPE_LOGIN => '登录',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'user_id', 'created_at'], 'integer'],
            [['content'], 'string'],
            [['item_id'], 'string', 'max' => 190],
            [['created_ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'user_id' => 'user_id',
            'content' => 'Content',
            'item_id' => 'item_id',
            'created_ip' => 'Ip',
            'created_at' => 'Created At',
        ];
    }

    public function behaviors()
    {
        return [
            ['class' => '\yii\behaviors\TimestampBehavior', 'updatedAtAttribute' => false],
            [
                'class' => '\yii\behaviors\AttributeBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_ip',
                ],
                'value' => Yii::$app->request->userIP,
            ],
        ];
    }

    public static function log($type, $content, $user_id = 0)
    {
        if ($user_id == 0) {
            $user_id = Yii::$app->user->id;
        }
        $model = new static();
        $model->setAttributes([
            'type' => $type,
            'user_id' => $user_id,
            'content' => $content,
        ]);
        return $model->save();
    }

    public static function adminLog($content, $item_id = '')
    {
        $model = new static();
        $model->setAttributes([
            'user_id' => 0,
            'content' => $content,
            'item_id' => $item_id,
        ]);
        return $model->save();
    }
}
