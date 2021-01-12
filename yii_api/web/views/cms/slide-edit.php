<?php
use \yii\helpers\Url;
use yii\widgets\ActiveForm;
use \yii\helpers\Html;
use app\models\Param;
use \app\common\models\Slide;

$this->title = ($model->isNewRecord ? '添加' : '编辑') . '轮播图';
$coverSize =  Param::getValue('system_slide_cover' , Slide::$coverSize);
;
?>

    <div class="page-content-wrap">
        <div class="tit">
            <?php $url = Url::to(['slide'])?>
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
                  <?= $form->field($model, 'title')->textInput(['class'=>'inp']) ?>

                  <?= $form->field($model, 'coverFile')->label('轮播图片')->fileInput(['class'=>'inpfile','style'=>'width:100%'])->hint("标准尺寸：$coverSize".'<br>'.($model->cover ?Html::img('@web' . $model->cover, ['width' => 210]) : false)) ?>


                  <?= $form->field($model, 'status')->dropDownList(Yii::$app->params['list']['qy_ty'],['style' => 'width: 80px;'])?>
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

