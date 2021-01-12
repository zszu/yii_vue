<?php
/* @var $this \yii\web\View */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use \app\common\models\Type;
use yii\helpers\Url;
use app\common\models\Service;
use app\components\Helper;
$type = Yii::$app->request->get('type');

$this->title = '需求管理';

?>

    <div class="elem_quote_search">
        <div class="inline">
             <?= $this->title?>
        </div>
         <div class="inline">
            <?= Html::a('添加', ['admin/message-edit'], ['class' => 'default_btn btn_add']) ?>
        </div>

    </div>
    <style>
    h3.tit {
        padding: 17px 20px;
        border-bottom: 1px solid #dee5e7;
        overflow: hidden;
        background: #fff;
        margin: 0 20px;
    }   
    </style>

   <h3 class="tit"><?= $this->title?></h3> 
    <div class="form_table_list">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'id'=>'grid',
            'showHeader' => true,
            'columns' => [
                // 'id',
                
                'name',
                'phone',
                'email',
                [
                    
                 'attribute'=>'created_at',
                 'value'=>function($model)
                     {
                         return date('Y-m-d H:i:s' , $model->created_at);
                     }
               ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '状态',
                    'template' => '{approve}',
                    'buttons' => [
                    'approve' => function ($url, $model, $key) {
                        if($model->status==1){
                            return  Html::tag('span' , '已审核' , ['style'=>'color:green;']);
                        }else{
                            return  Html::a('未审核', $url, ['class' => 'profile-link','style'=>'color:red']);
                        }
                    },
                    ],
                    'headerOptions' => ['width' => '80'],
                ],
               [
                   'class'=>'app\components\ActionColumn',
                   'template'=>'{delete}',
                   'item'=>'message',
                ],

             
            ],
        ]);?>
    </div>


