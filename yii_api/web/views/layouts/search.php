<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\common\models\SearchModel */
/* @var $form yii\widgets\ActiveForm */
$action = Yii::$app->controller->action->id;
?>
<style type="text/css">
    .inline input{
        display: inline;
    }
</style>

    <?php $form = ActiveForm::begin([
        'action' => [$action],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id')->textInput(['class'=>'inp']) ?>

    <?= $form->field($model, 'type')->textInput(['class'=>'inp']) ?>

    <?= $form->field($model, 'price')->textInput(['class'=>'inp']) ?>

    <?= $form->field($model, 'title')->textInput(['class'=>'inp']) ?>

    <?= Html::submitButton('搜索', ['class' => 'default_btn  btn-primary']) ?>
    <?= Html::resetButton('重置', ['class' => 'default_btn btn-default']) ?>


    <?php ActiveForm::end(); ?>

