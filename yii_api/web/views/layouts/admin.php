<?php
/* @var $this \yii\web\View */
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\Menu;
use app\models\Param;
use \yii\helpers\Url;
use app\components\Helper;

$action = Yii::$app->controller->action->id;
$this->title = $this->title . ' - 犀跃企业网站管理系统';

?>
<?php $this->beginContent('@app/views/layouts/base.php'); ?>
<!--//头部-->

<!--header-wrapper-->
    <div class="header">
        <div class="logo">
            <a href="<?= Url::toRoute(['site/index']);?>" target="_blank">
                 <?= Html::img('/images/admin/logo.png');?>
            </a>
        </div>
        <div class="hd_btn">
            <?=  Html::a(Yii::t('common' , '更新缓存'),['/admin/clear-cache'],['class'=>'update']), Html::a(Yii::t('common' , '退出'),['/admin/logout'],['class'=>'exit']);?>
        </div>
        <h2><?= Yii::t('common' , '管理后台');?></h2>
    </div>
    <div class="main">
        <div class="box_con_d1">
            <div class="col-l">
               <?= $this->render('admin_left');?>
            </div>

        <!--/left-menu-wrap-->
        <!--main-wrapper-->
            <div class="col-r">
                <?= $content ?>
                <div class="fd_copy">Powered by x-sure © 2019 <?= Yii::t('common' , '版权所有');?></div>
             </div>
        </div>
    </div>
<?php $this->endContent(); ?>