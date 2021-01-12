<?php
/* @var $this \yii\web\View */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

use yii\bootstrap\Modal;
$this->title = '分类管理';
?>

<!-- <div class="col-r"> -->

    <div class="elem_quote_search">
    <!--     <div class="inline pull-right">
            <?php  Html::a('产品列表',Url::to(['admin/product','name' => 1]),['class'=>'btn default_btn btn-lg active']);?> 
        </div> -->

        <div class="inline">
            <?= Html::a('添加', ['/admin/type-edit' , 'name' => $name], ['class' => 'default_btn btn_add','role'=>'button']) ?>
        </div>

         <div class="inline pull-right">
            <?=  Html::a('列表',Url::to(['admin/'.$name,'type' => $name]),['class'=>'btn default_btn btn-lg active']);?>
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
            'columns' => [
                //   [
                //     'class'=>\yii\grid\CheckboxColumn::className(),
                //     'name'=>'id',
                //     'checkboxOptions' => function ($model, $key, $index, $column)
                //     { return ['value'=>$model->id,'class'=>'checkbox']; }
                // ],
                // 'id',
                'order_by',
                // 'type',
                'title',
                // 'url:url',
                //'logo',
                //'updated_at',
                //'created_at',

                // [
                //     'attribute' => 'status',
                //     'class' => 'app\components\ListColumn',
                //     'list' => Yii::$app->params['list']['qy_ty'],
                // ],
                
//                [
//                    'attribute' => 'up_id',
//                    'label'=>'子类',
//                    'value' => function($model){
//                        $child =
//                        return $model->up_id;
//                    },
//                ],
                 [
                    'attribute' => 'status',
                    'class' => 'app\components\ListColumn',
                    'list' => Yii::$app->params['list']['qy_ty'],
                ],
                [
                    'class'=>'app\components\ActionColumn',
                    'item'=>'type',
                ]
                // [
                //     'class' => '\app\components\ActionColumn',
                //     'header' => '操作',
                //     'contentOptions' => [
                //     'width' => '200px',
                //      ],
                //     'template' => '{audit}',
                //     'buttons' => [
                //         'audit' => function ($url, $model, $key) {
                //             return
                //             Html::a('删除', ['admin/type-delete', 'id' => $model->id, 'name' => $model->name], ['class' => 'btn_mini btn_del' , 'data' => ['confirm' => '你确定要删除吗？']])    
                //             .Html::a('编辑', ['admin/type-edit', 'id' => $model->id, 'name' => $model->name], ['class' => 'btn_mini btn_edit']).'&nbsp;';
                //         },
                //     ],
                // ],

                
            ]
        ]);?>
    </div>

 