<?php

namespace app\common\models;

use Yii;
use app\components\RegularHelper;
use app\components\AppHelper;
use app\components\Helper;
use app\models\Param;

/**
 * This is the model class for table "tsj_message".
 *
 * @property string $id
 * @property int $order_by
 * @property string $type
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $content
 * @property string $ip
 * @property string $created_at
 * @property int $status
 * @property string $updated_at
 */
class Message extends \app\models\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','phone'], 'required'],
            [['order_by', 'type'], 'integer'],
            [['content'], 'string'],
            [['name'], 'string', 'max' => 20],
            [['email', 'phone','created_at','updated_at'], 'string', 'max' => 50],
            [['ip'], 'string', 'max' => 15],
            ['email', 'match', 'pattern' => RegularHelper::email(),'message' => '不是一个有效的电子邮箱'],
            ['name', 'match', 'pattern' => RegularHelper::name(),'message' => '不是一个有效的姓名'],
            ['phone', 'match', 'pattern' => RegularHelper::mobile(),'message' => '不是一个有效的电话'],
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
            'name' => '姓名',
            'email' => '邮箱',
            'phone' => '电话',
            'content' => '内容',
            'ip' => 'Ip',
            'created_at' => '创建时间',
            'status' => '状态',
            'updated_at' => '编辑时间',
        ];
    }
    public function behaviors()
    {
        return [
            ['class' => 'yii\behaviors\TimestampBehavior'],
            ['class' => 'app\components\behavior\RegularBehavior'],
        ];
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $site_name = Param::getValue('site_name');
            $site_domain = Param::getValue('site_name');
            $content = $site_name.'('.$site_domain.')收到一条留言，请注意查看！';
            AppHelper::sendMail('' , '留言',$content);
            return true;
        }
    }
}
