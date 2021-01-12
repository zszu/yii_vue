<?php
/* @var $this \yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Param;
$action = Yii::$app->controller->action->id;
// $actionName = $action == 'company-param'?'关于我们设置':;
$this->title = $param_type == ''?'基本设置':'参数设置';
$site_host_type_int = Param::findOne(['id' => '78'])->value;
?>
<div class="page-content-wrap">
  <h3 class="tit"><?= $this->title;?></h3> 
    <div class="content">
        <?php if($param_type != ''):?>
         <div class="tabs-container" >
            <ul class="text-center">
                <li class="btn-tab <?= $param_type=='item'?'tab-current':''?>"><a href="<?= Url::to(['admin/system-item-param'])?>">列表展示设置</a></li>
                <li class="btn-tab <?= $param_type=='wh'?'tab-current':''?>"><a href="<?= Url::to(['admin/system-wh-param'])?>">封面缩略图设置</a></li>
            </ul>
        </div>
        <?php endif;?>
        <!--set-detail-->
        <?= Html::beginForm('', 'post', [
            'enctype' => 'multipart/form-data',
        ]) ?>
        <table class="maintable" rules="all">
            <?php foreach ($params as $param): ?>
            
                   <tr <?= $param['name'] =='site_host_size'?'id="'.$param['name'].'"':'';?> <?= $param['name'] =='site_host_size' && $site_host_type_int == 1?'style="display:none"':'';?>>
                    <th>
                        <?php 
                          echo Html::label($param['title'], 'Params_' . $param['id']);
                        ?>
                    </th>
                    <td>
                        <?php
                        if($param['input_type'] == 'radio') {
                            echo Html::radioList("Params[{$param['id']}]", $param['value'], unserialize($param['input_list']), ['class'=> $param['id'],'id' => 'Params_' . $param['id'], 'separator'=>' ']);

                        } elseif($param['input_type'] == 'dropdown') {
                            echo Html::dropDownList("Params[{$param['id']}]", $param['value'], unserialize($param['input_list']), ['id' => 'Params_' . $param['id'], 'class' => 'select']);
                        } elseif($param['input_type'] == 'checkbox') {

                        } elseif($param['input_type'] == 'password') {
                            echo Html::passwordInput("Params[{$param['id']}]", $param['value'], ['id' => 'Params_' . $param['id'], 'size'=>90, 'style'=>"width: ".($param['width'] ? $param['width'] . 'px' : '100%'), 'class' => 'inp']);
                        } elseif($param['input_type'] == 'textarea') {

                            echo Html::textarea("Params[{$param['id']}]", $param['value'], ['id' => 'Params_' . $param['id'], 'style'=>"width: ".($param['width'] ? $param['width'] . 'px' : '100%'), 'rows'=>3, 'class' => 'tta']);
                        } elseif ($param['input_type'] == 'file') {
                            echo Html::fileInput("Params[{$param['id']}]", $param['value'], ['id' => 'Params_' . $param['id'], 'class' => 'upload-file','style'=>'width:100%']);
                            echo Html::img('@web'.$param['value'],['style'=>'width:150px;']);
                            echo Html::hiddenInput("Params[{$param['id']}]", $param['value']);
                            if($param['value']) echo Html::tag('p', Html::checkbox("Params_Del[{$param['id']}]", false, ['label' => '删除上传']));
                        } 
                         else {
                                echo Html::textInput("Params[{$param['id']}]", $param['value'], ['id' => 'Params_' . $param['id'], 'size'=>90, 'style'=>"width: ".($param['width'] ? $param['width'] . 'px' : '100%'), 'class' => 'inp']);
                        }
                        ?>
                        <?php if ($param['hint']): ?>
                          <br>
                          <br>
                          <span><?= $param['hint'] ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr class="btn_tab">
                <th></th>
                <td>
                    <button class="btn-normal">立即提交</button>
                </td>
            </tr>
        </table>
        <?= Html::endForm() ?>
        <!--/set-detail-->
    </div>
</div>
<script type="text/javascript">
   $("#Params_78").click(function(){
       var obj = document.getElementsByName("Params[78]")
            for (var i = 0; i < obj.length; i++) { //遍历Radio 
                if (obj[i].checked) {
                    item = obj[i].value;                   
                }
            }
        if(item == 1){
            $("#site_host_size").hide();
        }else{
             $("#site_host_size").show();
        }
    })

</script>


