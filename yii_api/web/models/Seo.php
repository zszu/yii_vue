<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tsj_seo}}".
 *
 * @property int $id
 * @property string $action
 * @property int $order_by
 * @property string $name
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $code
 * @property int $status
 * @property string $created_at
 * @property int $updated_at
 */
class Seo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%seo}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['action', 'name', 'title'], 'required'],
            [['order_by', 'status', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string'],
            [['action'], 'string', 'max' => 30],
            [['name'], 'string', 'max' => 50],
            [['title', 'keywords', 'code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'action' => 'Action',
            'order_by' => 'Order By',
            'name' => 'Name',
            'title' => 'Title',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'code' => 'Code',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    public function behaviors()
    {
        return [
            ['class' => 'yii\behaviors\TimestampBehavior'],
        ];
    }
}
