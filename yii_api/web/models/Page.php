<?php

namespace app\models;

use Yii;
use app\components\AppHelper;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;
/**
 * This is the model class for table "tsj_page".
 *
 * @property string $name
 * @property int $order_by
 * @property int $type
 * @property string $title
 * @property string $content
 * @property string $summary
 * @property string $bg_pic
 * @property int $status
 * @property string $updated_at
 * @property string $id
 */
class Page extends \yii\db\ActiveRecord
{
    public $coverFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'title'], 'required'],
            [['order_by', 'type', 'status', 'updated_at'], 'integer'],
            [['content', 'summary'], 'string'],
            [['name'], 'string', 'max' => 20],
            [['title'], 'string', 'max' => 50],
            [['bg_pic', 'id'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_by' => '排序',
            'type' => '类别',
            'title' => '标题',
            'content' => '内容',
            'summary' => '摘要',
            'bg_pic' => '背景图',
            'status' => '状态',
            'updated_at' => 'Update At',
        ];
    }
       public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => null,
            ],
        ];
    }
    public function beforeValidate(){

        $this->coverFile = UploadedFile::getInstance($this, 'coverFile');
        return parent::beforeValidate();
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
                 // 封面 $this->coverFile 是对象
             AppHelper::upload($this, $this->coverFile, 'bg_pic', 'page');
            return true;
        }
        return false;
    }
}
