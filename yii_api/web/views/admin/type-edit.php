<?php

use yii\helpers\Url;
use app\extensions\ZKindEditor\ZKindEditor;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\models\Param;
use app\common\models\Type;
$this->title = ($model->isNewRecord ? '添加' : '编辑') . '分类';
$name = Yii::$app->request->get('name');
?>
<div class="page-content-wrap">
    <div class="tit">
        <?php $url = Url::to(['type' , 'name' => $name])?>
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
            <?= $form->field($model, 'order_by')->textInput(['class'=>'inp_px']) ?>
            <?php $form->field($model, 'up_id')->dropDownList(['0'=>'顶级分类']+Type::listData($name),
                [
                    'options'=>[
                        $model->id => ['disabled' => true]
                    ]
                ])
            ?>
            <?= $form->field($model, 'title')->textInput(['class'=>'inp']) ?>
            <?= $form->field($model, 'seoTitle')->textInput(['class'=>'inp']) ?>
            <?= $form->field($model, 'seoKeywords')->textInput(['class'=>'inp']) ?>
            <?= $form->field($model, 'seoDescription')->textInput(['class'=>'inp']) ?>

            <?php $form->field($model, 'url')->textInput(['class'=>'inp']) ?>
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

