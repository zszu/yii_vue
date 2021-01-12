<?php

namespace app\common\models;

use Yii;
use yii\helpers\FileHelper;
use app\extensions\ZKindEditor\ZKindEditor;
use app\components\AppHelper;
use app\components\ZHelper;
use app\components\Helper;
use app\models\Param;
/**
 * This is the model class for table "{{%tsj_slide}}".
 *
 * @property string $id
 * @property int $order_by 排序
 * @property int $type 分类id
 * @property string $title 标题
 * @property string $subtitle 副标题
 * @property string $publisher 作者
 * @property string $summary 简介
 * @property string $content 内容
 * @property string $cover 封面
 * @property text $covers 多图
 * @property string $qr_code 二维码
 * @property string $bg_color 背景色
 * @property string $bg_pic 背景图
 * @property string $url 链接
 * @property string $tags 标签
 * @property string $views 查看数
 * @property int $index_show 首页显示
 * @property string $created_at
 * @property int $updated_at
 * @property int $status 状态：1 启用 0停用
 */
class Slide extends \app\models\ActiveRecord
{
    public $coverFile;
    public $qrCodeFile;
    public $coversFile;
    public $photoArr = [];
    //  图片尺寸
    public static $coverSize = '';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%slide}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_by', 'type', 'views', 'index_show', 'created_at', 'updated_at', 'status'], 'integer'],
//            [['title'], 'required'],
            [['summary', 'content'], 'string'],
            [['title', 'subtitle', 'publisher', 'cover', 'qr_code', 'bg_color', 'bg_pic', 'url', 'tags'], 'string', 'max' => 255],
            [['coverFile'], 'image', 'extensions' => 'png,jpg,jpeg,gif', 'checkExtensionByMimeType' => false],
            [['qrCodeFile'], 'image', 'extensions' => 'png,jpg,jpeg,gif', 'checkExtensionByMimeType' => false],
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
            'type' => '分类',
            'title' => '标题',
            'subtitle' => '副标题',
            'publisher' => '作者',
            'summary' => '简介',
            'content' => '内容',
            'cover' => '封面',
            'covers' => '多图',
            'qr_code' => '二维码',
            'bg_color' => '背景色',
            'bg_pic' => '背景图',
            'url' => '链接',
            'tags' => '标签',
            'views' => '查看数',
            'index_show' => '首页显示',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'coverFile' => '封面',
            'qrCodeFile' => '二维码',
            'coversFile' => '多图',
        ];
    }
    public function beforeSave($insert){
        if(Parent::beforeSave($insert)){
                //封面
              $size = Param::getValue('system_slide_cover' , self::$coverSize);
              AppHelper::upload($this, $this->coverFile, 'cover', 'slide',$size);
              return true;
        }
        return false;
    }


    public function behaviors()
    {
        return [
            ['class' => 'yii\behaviors\TimestampBehavior'],
        ];
    }
}
