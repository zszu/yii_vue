<?php
use \yii\helpers\Url;
use app\components\ActiveForm;
use \yii\helpers\Html;
use app\models\Param;
use app\extensions\ZKindEditor\ZKindEditor;

$this->title = ($model->isNewRecord ? '添加' : '编辑') . 'seo';
$coverSize = Param::getValue('system_slide_cover','');
$coversSize = '';
?>

    <div class="page-content-wrap">
        <div class="tit">
            <?php $url = Url::to(['seo'])?>
            <?= Html::tag('h3',$this->title) , Html::a('返回',$url,['class' => 'btn-last-page']);?>
        </div>
        <div class="content">
            <table cellspacing="0" cellpadding="0" width="100%" class="maintable">
                  <?php $form = ActiveForm::begin([
                        'options' => [
                            'enctype' => 'multipart/form-data',
                        ],
                        'validateOnSubmit' => true,
                    ]) ?>
                <tbody>
                    <?= $form->field($model, 'order_by')->textInput(['class'=>'inp_px'])->hint('<span>数字越大越靠前</span>') ?>
                    <?= $form->field($model, 'action')->textInput(['class'=>'inp']) ?>
                    <?= $form->field($model, 'name')->textInput(['class'=>'inp']) ?>
                    <?= $form->field($model, 'title')->textInput(['class'=>'inp']) ?>
                    <?= $form->field($model, 'keywords')->textInput(['class'=>'inp']) ?>
                    <?= $form->field($model, 'description')->textInput(['class'=>'inp']) ?>
                    <?= $form->field($model, 'code')->textInput(['class'=>'inp']) ?>
                
                </tbody>
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

