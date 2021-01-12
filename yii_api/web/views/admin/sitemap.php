<?php
/* @var $this \yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Param;
$action = Yii::$app->controller->action->id;
// $actionName = $action == 'company-param'?'关于我们设置':;
$this->title = '站点地图';
$site_host_type_int = '1';
?>



    <div class="page-content-wrap">
      <h3 class="tit">站点地图</h3> 
        <div class="content">
            <!--set-detail-->
            <?= Html::beginForm('', 'post', [
                'enctype' => 'multipart/form-data',
            ]) ?>
            <style type="text/css">
                .td-width{
                    width:100px;
                }
            </style>
            <table class="maintable" rules="all">
<!--                <tr>-->
<!--                    <th>-->
<!--                        信息源：-->
<!--                    </th>-->
<!--                    <td>-->
<!--                        /rss.xml-->
<!--                    </td>-->
<!--                </tr>-->
                <tr>
                    <th>
                        XML站点地图：
                    </th>
                    <td>
                        /sitemap.xml
                    </td>
                </tr>
<!--                <tr>-->
<!--                    <th>-->
<!--                        HTM站点地图：-->
<!--                    </th>-->
<!--                    <td>-->
<!--                        /sitemap.htm-->
<!--                    </td>-->
<!--                </tr>-->
                <tr>
                    <th>
                        TXT站点地图：
                    </th>
                    <td>
                        /sitemap.txt
                    </td>
                </tr>
               <!--  <tr>
                    <th>
                        更新城市分站
                    </th>
                    <td>
                    </td>
                </tr> -->
               
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


