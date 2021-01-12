<?php
use yii\widgets\ActiveForm;
$this->title = '修改密码';
?>
<!-- <div class="col-r"> -->

    <div class="page-content-wrap">
        <h3 class="tit">管理员密码修改</h3>
        <div class="content">

            <table cellspacing="0" cellpadding="0" width="100%" class="maintable">
                <?php $form = ActiveForm::begin([
                    'options' => [
                        'enctype' => 'multipart/form-data',
                    ],
                    'validateOnSubmit' => true,
                ]) ?>
                    <tbody>



                    <?= $form->field($model, 'oldPassword')->passwordInput(['class'=>'inps']) ?>


                   <?= $form->field($model, 'password')->passwordInput(['class'=>'inps']) ?>


                    <?= $form->field($model, 'passwordRepeat')->passwordInput(['class'=>'inps']) ?>



                    <tr class="btn_tab">
                        <th></th>
                        <td>
                            <button class="btn-normal">提交保存</button>
                        </td>
                    </tr>
                    <?php ActiveForm::end() ?>
                    </tbody>

            </table>



        </div>
    </div>


