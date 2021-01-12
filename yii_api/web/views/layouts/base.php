<?php

use yii\helpers\Html;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language?>">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1" />
    <meta name="renderer" content="webkit" />
    <meta name="format-detection" content="telephone=no" />
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <link rel="stylesheet" href="/css/admin/autoCheck.css">
    <link rel="stylesheet" href="/css/admin/style.css" />
    <script src="/js/admin/jquery.min.js"></script>
    <script src="/js/admin/lib.js"></script>
</head>

<body>

<!--[if lt IE 8]>
<style>
/*IE兼容性*/
.upgradeBrowser{ background:#ffffe1;border-bottom:1px solid #f90;}
.upgradeBrowserBox{width:1000px;margin:0 auto;line-height:46px; text-align:center;color:#f60;}
</style>
<div class="upgradeBrowser"><div class="upgradeBrowserBox"><span>Hi，您的IE浏览器版本过低，请升级您的浏览器至IE9+，以享受更优质的浏览效果！:)</span></div></div>
<![endif]-->
<?php $this->beginBody() ?>
    <?= $content ?>
<?php $this->endBody() ?>
<?php $action = \Yii::$app->controller->action->id;?>
<?php if($action=='index'):?>

    <!-- 网站体检 -->
<!-- <div class="pop-box">
    <div class="cont" id="pop-con1">
        <div class="tit"><span>思默机器人正在检测您的网站</span></div>
        <div class="circleChart" id="chart2"></div>
        <div class="con"></div>
        <p class="p1">请耐心等待正在体检中...</p>
    </div>
    <div class="cont" id="pop-con2">
        <div class="tit"><span>您还有提升的空间哦</span></div>
        <div class="circleChart" id="chart3"></div>
        <ul class="list">
            <li><span>填写了网站介绍</span><a href="" class="more">前往 &gt;</a></li>
            <li><span>设置了网站图标</span><a href="" class="more">前往 &gt;</a></li>
            <li><span>上传了分享封面图</span><a href="" class="more">前往 &gt;</a></li>
            <li class="ok"><span>最新版网站系统</span><a href="" class="more">前往 &gt;</a></li>
            <li><span>SEO设置</span><a href="" class="more">前往 &gt;</a></li>
            <li><span>发布10篇文章</span><a href="" class="more">前往 &gt;</a></li>
            <li class="ok"><span>开启站点地图</span><a href="" class="more">前往 &gt;</a></li>
        </ul>
        <div class="bot">
            <p>体检完成</p>
            <a href="javascript:void(0);" class="js-close">已阅</a>
        </div>
    </div>
</div>
    -->
<script src="/js/admin/AutoCheck.js"></script>
<script src="/js/admin/autoCheckDemo.js"></script>
<script>

    $('.js-show').click(function(event) {
        $('.pop-box').show();
        $('#pop-con1').show();
        AutoCheck.run(checkConfig);

        $("#chart2").circleChart({
            size: 68,
            value: 100,
            speed: 7000,
            text: 0,
            startAngle: 75,
            redraw: "truw",
            animation: 'linearTween',
            onDraw: function(el, circle) {
                circle.text(Math.round(circle.value) + "%");
            }
        });
    });
    $('.js-close').click(function(event) {
        $('#autoCheckModal').remove();
        $('.pop-box,#pop-con2').hide();
    });

</script>
<script src="/js/admin/circleChart.js"></script>
<script>
    $("#chart3").circleChart({
        size: 68,
        value: 30,
        text: 0,
        speed: 1000,
        startAngle: 75,
        animation: 'linearTween',
        onDraw: function(el, circle) {
            circle.text(Math.round(circle.value) + "%");
        }
    });
</script>
<?php endif;?>




</body>
</html>

<?php $this->endPage() ?>
