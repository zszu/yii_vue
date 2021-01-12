<?php

namespace app\common\models;

use Yii;

/**
 * This is the model class for table "tsj_link".
 *
 * @property int $id
 * @property int $order_by
 * @property int $type
 * @property string $name
 * @property string $url
 * @property string $title
 * @property string $cover
 * @property int $status
 * @property int $updated_at
 * @property int $created_at
 */
class Link extends \yii\db\ActiveRecord
{
    public $coverFile;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%link}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_by', 'type', 'updated_at', 'created_at','status'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['url', 'title', 'cover'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_by' => '排序',
            'type' => '类别',
            'name' => 'Name',
            'url' => 'url',
            'title' => '标题',
            'cover' => '图片',
            'status' => '状态',
            'updated_at' => '编辑时间',
            'created_at' => '创建时间',
        ];
    }
    public function behaviors()
    {
        return [
            ['class' => 'yii\behaviors\TimestampBehavior'],
        ];
    }
}
