<?php

namespace app\common\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\components\AppHelper;
use yii\web\UploadedFile;
use app\models\Param;
use app\components\PingYin;
/**
 * This is the model class for table "{{%tsj_type}}".
 *
 * @property string $id
 * @property string $up_id
 * @property int $level
 * @property int $order_by
 * @property string $name
 * @property string $title
 * @property string $subtitle
 * @property string $cover
 * @property string $summary
 * @property string $bg_pic
 * @property string $bg_color
 * @property string $url
 * @property int $status
 * @property string $updated_at
 * @property string $created_at
 * @property string $covers
 */
class Type extends \app\models\ActiveRecord
{
    public $coverFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['up_id', 'level', 'order_by', 'status', 'updated_at', 'created_at'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['title', 'subtitle'], 'string', 'max' => 50],
            [['cover', 'summary', 'bg_pic', 'url', 'covers','seoTitle','seoKeywords','seoDescription'], 'string', 'max' => 255],
            [['bg_color'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'up_id' => '上级id',
            'level' => '层级',
            'order_by' => '排序',
            'name' => 'Name',
            'title' => '标题',
            'subtitle' => '副标题',
            'cover' => '封面',
            'summary' => '简介',
            'bg_pic' => 'Bg Pic',
            'bg_color' => 'Bg Color',
            'url' => 'Url',
            'status' => '状态',
            'coversFile' => '多图',
            'updated_at' => '编辑时间',
            'created_at' => '创建时间',
            'seoTitle' => 'seo标题',
            'seoKeywords' => '关键词',
            'seoDescription' => '描述',

        ];
    }
    public static function listData($name='')
    {
        $models = static::find()->where(['status' => 1])
                              ->andFilterWhere(['name' => $name])
                              ->orderBy('order_by desc , created_at desc')
                              ->all();
        $data = AppHelper::cateGroy($models);
         foreach($data as $k=>$v){

                $data[$k]['title'] = str_repeat("|--",$data[$k]['level']-1).$data[$k]['title'];
            }
        return ArrayHelper::map($data,'id','title');
    }


    public function beforeValidate(){

        $this->coverFile = UploadedFile::getInstance($this, 'coverFile');

        return parent::beforeValidate();
    }
    public function beforeSave($insert){
        if(parent::beforeSave($insert)){
               $this->name = Yii::$app->request->get('name');

               if(Type::findOne($this->up_id)){
                    $this->level = (Type::findOne($this->up_id)->level)+1;
               }else{
                    $this->level = 1;
               }
                $this->english_name = PingYin::encode($this->title , 'all');

               // 封面
                AppHelper::upload($this, $this->coverFile, 'cover', 'type',Param::getValue('system_type_cover','460*300'));
                return true;
        }
    }
    public function behaviors()
    {
        return [
            ['class' => 'yii\behaviors\TimestampBehavior'],
        ];
    }
}
