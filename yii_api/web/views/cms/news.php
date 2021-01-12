<?php
/* @var $this \yii\web\View */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use \app\common\models\Type;
use yii\helpers\Url;
use app\common\models\Service;
$type = Yii::$app->request->get('type');

$this->title = '新闻管理';

?>

    <div class="elem_quote_search">
        <div class="inline">
            <?= Html::a('添加', ['admin/news-edit' , 'name'=>'news'], ['class' => 'default_btn btn_add']) ?>
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
                'order_by',

                'title',
                [
                    'attribute' => 'cover',
                    'format' => [
                        'image',
                        [
                            'width'=>'220px',
                        ]
                    ],
                    'value' => function ($model) {
                        return $model->cover;
                    }
                ],
                [
                    
                    'attribute'=>'created_at',
                    'format'=>['date','php:Y-m-d H:i:s']
               ],
     
                [
                    'attribute' => 'status',
                    'class' => 'app\components\ListColumn',
                    'list' => Yii::$app->params['list']['qy_ty'],
                ],
                [
                    'class' => 'app\components\ActionColumn',
                    'item'=>'news',
                ],

            ],
        ]);?>
    </div>


