<?php
use yii\helpers\Url;
use \app\components\ZHelper;
use yii\widgets\ActiveForm;
?>

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
        'action' => Url::to(['site/images']),
        'validateOnSubmit' => true,
    ]) ?>
    <input type="file" name="file">
    <button type="submit">提交</button>
<?php ActiveForm::end();?>