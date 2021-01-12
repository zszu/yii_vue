<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
$action = Yii::$app->controller->action->id;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
<meta name="renderer" content="webkit" />
<meta http-equiv="Cache-Control" content="no-transform" /><!-- 禁止百度搜索转码 -->
<meta http-equiv="Cache-Control" content="no-siteapp" /><!-- 禁止百度手机转码 -->
<meta name="applicable-device"content="pc,mobile"><!-- 响应式网站 -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no" />
<meta name="format-detection" content="telephone=no" />
<?php $this->registerCsrfMetaTags() ?>
<title><?= Html::encode($this->title) ?></title>
<?php $this->head() ?>


<!--<script src="/site/js/jquery.waypoints.min.js"></script>-->
<!--<script src="/site/js/jquery.countup.js"></script>-->
<link rel="alternate" type="application/vnd.wap.xhtml+xml" media="handheld" href=""/>


</head>

<body>
<?php $this->beginBody() ?>

<?= $content ?>

<?php $this->endBody() ?>


<?php $this->endPage() ?>
</body>
</html>
