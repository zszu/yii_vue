<?php
/* @var $this \yii\web\View */
/* @var $model \app\models\Goods */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\common\models\Type;
use app\models\Param;
use app\extensions\ZKindEditor\ZKindEditor;


$this->title = ($model->isNewRecord ? '添加' : '编辑') . '关于我们';

// \app\assets\DateTimePickerAsset::register($this);
$coverSize = Param::getValue('system_page_cover','960*1080');
$qrCodeSize = Param::getValue('system_news_qr_code_cover','');
$coversSize = Param::getValue('system_news_covers','');
?>
    <div class="page-content-wrap">
        <div class="tit">
            <?php $url = Url::to(['page'])?>
            <?= Html::tag('h3',$this->title),Html::a('返回',$url,['class'=>'btn-last-page'])?>
        </div>
        <div class="content">
            <table cellspacing="0" cellpadding="0" width="100%" class="maintable">
                <?php $form = ActiveForm::begin([
                'options' => [
                    'enctype' => 'multipart/form-data',
                ],
                'validateOnSubmit' => true,
                ]) ?>
                <?= $form->field($model, 'name')->textInput(['class'=>'inp']) ?>
                <?= $form->field($model, 'title')->textInput(['class'=>'inp']) ?>
                <?= $form->field($model, 'summary')->textarea(['style'=>'width:100%']) ?>
                <?= $form->field($model, 'coverFile')->label('图片')->fileInput(['class'=>'inpfile','style'=>'width:100%'])->hint('<br>'.Html::img('@web' . $model->bg_pic, ['width' => 210]) ) ?>
                <?= $form->field($model, 'content')->widget(ZKindEditor::className(), [
                    'style' => ZKindEditor::STYLE_ADAVANCED,
                ]) ?>


                 <tr class="btn_tab">
                        <th></th>
                        <td>
                            <button class="btn-normal">立即提交</button>
                        </td>
                </tr>
                <?php ActiveForm::end() ?>

            </table>

        </div>
    </div>



