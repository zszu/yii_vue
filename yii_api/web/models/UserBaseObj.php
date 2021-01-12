<?php
namespace app\models;

use app\components\Helper;
use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;


class UserBaseObj extends User implements IdentityInterface
{
    //检查用户组
    protected static function checkIdentity($identity)
    {
        // if ($identity && in_array($identity->group, [self::GROUP_MEMBER, self::GROUP_COMPANY])) {
        //     return $identity;
        // }

        return $identity;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::checkIdentity(static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]));
    }


    /**
     * {@inheritdoc}
     */
//    public static function findIdentityByAccessToken($token, $type = null)
//    {
//        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
//    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // 如果token无效的话，
        if(!static::accessTokenIsValid($token)) {
            throw new \yii\web\UnauthorizedHttpException("token is invalid.");
        }

        return static::findOne(['access_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }
    /**
     * 生成 access_token
     */
    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    public function generateInvalidAt(){
        $this->invalid_at = time()+Yii::$app->params['user.accessTokenExpire'];
    }

    /**
     * 校验 access_token 是否有效
     */
    public static function accessTokenIsValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.accessTokenExpire'];
        return ($timestamp + $expire >= time());
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::checkIdentity(static::findOne(['username' => strtolower($username), 'status' => self::STATUS_ACTIVE]));
    }

    public static function findByMobile($mobile)
    {
        return static::checkIdentity(static::findOne(['mobile' => $mobile, 'status' => self::STATUS_ACTIVE]));
    }

    public static function findByEmail($email)
    {
        return static::checkIdentity(static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]));
    }

    public static function findByOpenId($openId)
    {
        return static::checkIdentity(static::findOne(['open_id' => $openId, 'status' => self::STATUS_ACTIVE]));
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getShowName()
    {
        return Helper::hiddenMiddle($this->mobile);
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
        $this->generateAuthKey();
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
