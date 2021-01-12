<?php
/* @var $this \yii\web\View */
/* @var $model \app\models\Goods */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\common\models\Type;
use app\models\Param;
use app\extensions\ZKindEditor\ZKindEditor;

$this->title = ($model->isNewRecord ? '添加' : '编辑') .'友链';

$coverSize = Param::getValue('system_link_cover','550*310');


?>

    <div class="page-content-wrap">
        <div class="tit">
            <?php $url = Url::to(['link'])?>
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
                

                <?= $form->field($model, 'order_by')->textInput(['class'=>'inp_px'])->hint('<span>数字越大越靠前</span>') ?>
                <?= $form->field($model, 'title')->textInput(['class'=>'inp']) ?>
                <?= $form->field($model, 'url')->textInput(['class'=>'inp']) ?>


                <?= $form->field($model, 'coverFile')->label('图片')->fileInput(['class'=>'inpfile','style'=>'width:100%'])
                    ->hint("标准尺寸：$coverSize".'<br>'.($model->cover ? Html::img('@web' . $model->cover, ['class' => 'img-cover','style'=>'width:200px;']) : false)) ?>

                <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['list']['qy_ty'],['style' => 'width: 100px;']) ?>
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
