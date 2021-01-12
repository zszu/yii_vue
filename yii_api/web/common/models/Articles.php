<?php
namespace app\common\models;

use app\models\ActiveRecord;

/**
 * This is the model class for table "{{%zz_articles}}".
 *
 * @property int $id
 * @property int|null $order_by 排序
 * @property int|null $type 分类id
 * @property string $title 标题
 * @property string|null $subtitle 副标题
 * @property string|null $publisher 作者
 * @property string|null $summary 简介
 * @property string|null $content 内容
 * @property string|null $cover 封面
 * @property string|null $covers 多图
 * @property string|null $qr_code 二维码
 * @property string|null $bg_color 背景色
 * @property string|null $bg_pic 背景图
 * @property string|null $url 链接
 * @property string|null $tags 标签
 * @property int|null $views 查看数
 * @property int|null $index_show 首页显示
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $status 状态：1 启用 0停用
 * @property string|null $name 所属栏目
 * @property string|null $keywords
 */
class Articles extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%articles}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_by', 'type', 'views', 'index_show', 'created_at', 'updated_at', 'status'], 'integer'],
            [['title'], 'required'],
            [['summary', 'content'], 'string'],
            [['title', 'subtitle', 'publisher', 'cover', 'covers', 'qr_code', 'bg_color', 'bg_pic', 'url', 'tags', 'name', 'keywords'], 'string', 'max' => 255],
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
            'subtitle' => '副标题',
            'publisher' => '作者',
            'summary' => '简介',
            'content' => '内容',
            'cover' => '图片',
            'covers' => '多图',
            'qr_code' => '二维码',
            'bg_color' => 'Bg Color',
            'bg_pic' => 'Bg Pic',
            'url' => 'Url',
            'tags' => '标签',
            'views' => '查看数',
            'index_show' => 'Index Show',
            'created_at' => '创建时间',
            'updated_at' => '编辑时间',
            'status' => '状态',
            'name' => 'Name',
            'keywords' => '关键词',
        ];
    }
}