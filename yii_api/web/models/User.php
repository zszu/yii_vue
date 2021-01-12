<?php

namespace app\models;
use Yii;
/**
 * This is the model class for table "{{%tsj_user}}".
 *
 * @property int $id
 * @property string $username 用户名
 * @property string $auth_key cookie key
 * @property string $password_hash 登录密码
 * @property string $password_reset_token 重置token
 * @property string $access_token api token
 * @property int $invalid_at api 有效期
 * @property string $mobile 手机
 * @property string $email 邮箱
 * @property int $group 群组
 * @property string $avatar 头像
 * @property int $status 状态
 * @property string $created_ip 注册IP
 * @property string $last_login_ip 最后登录IP
 * @property int $last_login_at 最后登录时间
 * @property int $login_times 登录次数
 * @property int $created_at
 * @property int $updated_at
 * @property string $name 姓名
 * @property int $gender 性别
 * @property string $nickname 昵称
 * @property string $open_id OPEN ID
 * @property string $union_id UNION ID
 * @property int $order_by
 */

class User extends  \yii\db\ActiveRecord 
{
    const STATUS_DELETED = -1;
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    public static $statusNames = [
        self::STATUS_DELETED => '删除',
        self::STATUS_INACTIVE => '停用',
        self::STATUS_ACTIVE => '启用',
    ];

    public static function tableName()
    {
         return '{{%user}}';
       
    }

 
     /**
      * @param $event UserEvent
      */
    public static function afterLogin($event)
    {
        /* @var $identity User */
        $identity = $event->identity;
        UserLog::log(UserLog::TYPE_LOGIN, '后台登录!', $identity->id);
        $identity->last_login_ip = Yii::$app->request->userIP;
        $identity->last_login_at = time();
        $identity->login_times++;


        $identity->save();
    }
       /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            ['class' => '\yii\behaviors\TimestampBehavior'],
            ['class' => 'app\components\behavior\IpBehavior', 'locationAttribute' => null],
        ];
    }
    public static function afterLogout($event)
    {
        UserLog::log(UserLog::TYPE_LOGIN, '后台退出!', $event->identity->id);
    }

}
