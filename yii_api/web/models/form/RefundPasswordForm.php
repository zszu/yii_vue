<?php

namespace app\models\form;

use app\components\AppHelper;
use app\models\Param;
use Yii;
use yii\base\Model;

/**
 * 修改密码Form Model
 */
class RefundPasswordForm extends Model
{
    public $oldPassword;
    public $password;
    public $passwordRepeat;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['password', 'passwordRepeat'], 'required'],
            [['password'], 'string', 'max' => 50, 'min' => 6],
            ['passwordRepeat', 'compare', 'compareAttribute' => 'password', 'message' => '两次密码不一致'],

            ['oldPassword', 'validatePassword', 'skipOnEmpty' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'oldPassword' => '原密码',
            'password' => '新密码',
            'passwordRepeat' => '重复密码',
        ];
    }

    /**
     * 验证原密码 Model 验证函数
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        $savePassword = Param::getValue('system_refund_password');
        if ($savePassword) {
            if (empty($this->oldPassword)) {
                $this->addError($attribute, '原密码不正确');
            } elseif (!Yii::$app->security->validatePassword($this->oldPassword, $savePassword)) {
                $this->addError($attribute, '原密码不正确');
            }
        }
    }

    public function doSave()
    {
        if ($this->validate()) {
            Param::setValue('system_refund_password', Yii::$app->security->generatePasswordHash($this->password));

            AppHelper::sendMail(null, '退款密码已修改', '退款密码已被修改!');
            return true;
        }

        return false;
    }
}
