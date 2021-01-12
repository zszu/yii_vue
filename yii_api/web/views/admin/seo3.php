<?php

use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\Html;
use app\components\Helper;
$this->title = 'seo管理';
?>

    <div class="elem_quote_search">
    
        <div class="inline">
            <a class="default_btn btn_add" href="<?= Url::to(['admin/seo-edit'])?>">新增</a>
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
            'columns' => [
                [
                    'attribute'=>'order_by',
                    'label'=>'排序'
                ],
                'action',
                'name',
                'keywords',
                'description',
                'title',
                 // 'summary',
                
                [
                    'attribute'=>'created_at',
                    'label'=>'编辑时间',
                    'value' => function ($model) {
                        return date('Y-m-d H:i:s',$model->created_at);
                    }
                ],
                [
                'class'=>'app\components\ActionColumn',
                'item'=>'seo',
                ]
            
            ],
        ]);?>

    </div>
