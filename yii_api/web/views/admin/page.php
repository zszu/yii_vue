<?php
/* @var $this \yii\web\View */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use \app\common\models\Type;

$this->title = '关于我们管理';
?>
    <!-- <div class="col-r"> -->

<style>
	h3.tit {
		padding: 17px 20px;
		border-bottom: 1px solid #dee5e7;
		overflow: hidden;
		background: #fff;
		margin:20px 20px 0 20px;
	}	
	</style>
   <h3 class="tit">关于我们</h3>
    <div class="form_table_list">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                // 'name',
                'title',
                //'content',
                // [
                //     'attribute'=>'content',
                //     'format'=>[
                //         'text',
                //       'width'=>'300px',
                //     ],
                //     'value'=>function($model){
                //         return str_replace('</p>', '', str_replace('<p>', '', mb_substr($model->content,0,100)));
                //     }
                // ],
                // [
                //     'class'=>'app\components\ActionColumn',
                //     'item'=>'page',
                // ]
                [

                    'class' => 'yii\grid\ActionColumn',
                    'header' => '操作',
                     'contentOptions' => [
                    'width' => '200px',
                     ],
                    'template' => '{audit}',
                    'buttons' => [
                        'audit' => function ($url, $model, $key) {
                            return
                                    Html::a('编辑', ['admin/page-edit', 'id'=>$model->id], ['class' => 'btn_mini btn_edit']);
                                    // Html::a('删除', ['admin/page-delete', 'id'=>$model->id,'name' => $model->name], ['class' => 'btn_mini btn_del']);
                               
                        },
                    ],
                    'headerOptions' => ['width' => '80'],
                ],



            ]
        ]);?>
    </div>


    <div class="top-box">
   
</div>
