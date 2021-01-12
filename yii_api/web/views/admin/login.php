<?php
/* @var $this \yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->context->layout = 'base';
$this->title = '登录';
// \app\assets\AdminAsset::register($this);
?>
<!--login-wrapper-->
<div class="login-wrapper">
    <!--login-box-->
    <div class="login-box">
        <?php $form = ActiveForm::begin([
            'id' => 'formLogin',
            'fieldConfig' => [
                'template' => '{input}{error}',
                'options' => ['class' => null],
                'inputOptions' => ['class' => null],
            ],
        ]) ?>
            <h3>后台管理系统</h3>
            <div class="ipt-txt">
                <?= $form->field($model, 'username')->textInput(['placeholder' => '请输入账号']) ?>
            </div>
            <div class="ipt-txt">
                <?= $form->field($model, 'password')->passwordInput(['placeholder' => '请输入密码']) ?>
            </div>
            <div class="ipt-txt verify">
                <?php echo $form->field($model, 'verifyCode')
                    ->widget(\yii\captcha\Captcha::className(), [
                        'captchaAction' => ['admin/captcha'],
                        'template' => "{input}\n {image}",
                        'options' => [
                            'placeholder' => '请输入验证码',
                        ],
                        'imageOptions' => [
                            'class' => 'code',
                            'style' => 'vertical-align:middle; cursor: pointer;',
                            'title' => '登陆验证码，点击刷新',
                        ],
                    ]);
                ?>
            </div>
            <?= Html::submitButton('立即登录', ['class' => 'btn-submit', 'id' => 'btnSubmit']) ?>
        <?php ActiveForm::end() ?>
        <p class="foot">Powered by x-sure © 2019 版权所有</p>
    </div>
    <!--/login-box-->
</div>
<!--/login-wrapper-->
<link rel="stylesheet" href="/css/admin/login.css">
