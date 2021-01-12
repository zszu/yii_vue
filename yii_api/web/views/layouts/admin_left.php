<?php
use \yii\helpers\Url;
use app\assets\AdminAsset;

$action = Yii::$app->controller->action->id;
AdminAsset::register($this);
$nameArr = ['product', 'product-edit', 'slide', 'slide-edit', 'news', 'news-edit', 'down', 'down-edit',
'question', 'question-edit', 'cases', 'cases-edit', 'page', 'page-edit', 'type', 'type-edit', 'message', 'approve','message-edit'
];

$sysArr = [
        'site-param','seo','seo-edit','system-param','email-param','sitemap'
];
$name = Yii::$app->request->get('name');
?>


<div class="m_slide_d1">
    <div class="content">
        <div class="item">
            <a href="<?= Url::to(['/admin/index'])?>" class="add"><i style="background-image:url(/images/admin/icon_d10.png);"></i>管理主页</a>
        </div>
        <div class="item">
            <h3>主要功能</h3>
            <ul class="list_lk_d1">
                <li class="dlOn  <?= in_array($action , $sysArr)?'show':''?> ">
                    <dl>
                        <dt>
                            <a  href=""><i style="background-image:url(/images/admin/icon_d5.png);"></i>网站设置</a>
                        </dt>
                        <dd  
                        <?= in_array($action , $sysArr)?'style="display: block"':''?> >
                            <a  <?= $action=='site-param'?'class=\'on\'':''?>href="<?= Url::to(['/admin/site-param'])?>"><i style="background-image:url(/images/admin/icon_dian.png);"></i>基本设置</a>
                            <a <?= $action=='seo' || $action=='seo-edit'?'class=\'on\'':''?> href="<?= Url::to(['admin/seo'])?>"><i style="background-image:url(/images/admin/icon_dian.png);"></i>SEO设置</a>
                            <a <?= $action=='sitemap'?'class=\'on\'':''?> href="<?= Url::to(['admin/sitemap'])?>"><i style="background-image:url(/images/admin/icon_dian.png);"></i>站点地图设置</a>
                            <a <?= $action=='email-param'||$action=='email-param-edit'?'class=\'on\'':''?> href="<?= Url::to(['/admin/email-param'])?>"><i style="background-image:url(/images/admin/icon_dian.png);"></i>邮箱管理</a>
                            <a <?= $action=='system-param'?'class=\'on\'':''?> href="<?= Url::to(['/admin/system-param'])?>"><i style="background-image:url(/images/admin/icon_dian.png);"></i>网站开关设置</a>
                        </dd>
                    </dl>
                </li>
                <li><a <?= $action=='reset-pwd'?'class=\'on\'':''?> href="<?= Url::to(['/admin/reset-pwd'])?>"><i style="background-image:url(/images/admin/icon_d17_1.png);"></i>修改密码</a></li>
                <li><a <?= $action=='statistic'?'class=\'on\'':''?> href="<?= Url::to(['/admin/statistic'])?>"><i style="background-image:url(/images/admin/icon_d18_1.png);"></i>访问统计</a></li>
            </ul>
        </div>
        <div class="item">
            <h3>信息管理</h3>
            <ul class="list_lk_d1">
                <li class="dlOn <?= in_array($action , $nameArr)?'show':''?>">
                    <dl>
                        <dt>
                            <a href=""><i style="background-image:url(/images/admin/icon_d19_1.png);"></i>栏目管理</a>
                        </dt>
                        <dd <?= in_array($action , $nameArr)?'style="display: block"':''?>>
                         <a <?= $action=='slide' ||$action=='slide-edit'?'class=\'on\'':''?> href="<?= Url::to(['/admin/slide'])?>"><i style="background-image:url(/images/admin/icon_dian.png);"></i>轮播图管理</a>

                         <a <?= $action=='product' ||$action=='product-edit' || $name =='product'?'class=\'on\'':''?> href="<?= Url::to(['admin/product'])?>"><i style="background-image:url(/images/admin/icon_dian.png);"></i>产品管理</a>
                         <a <?= $action=='news' ||$action=='news-edit' || $name =='news'?'class=\'on\'':''?> href="<?= Url::to(['/admin/news'])?>"><i style="background-image:url(/images/admin/icon_dian.png);"></i>资讯管理</a>
                         <a <?= $action=='message'?'class=\'on\'':''?> href="<?= Url::to(['/admin/message'])?>"><i style="background-image:url(/images/admin/icon_dian.png);"></i>留言管理</a>

                         <a <?= $action=='page'||$action=='page-edit'?'class=\'on\'':''?> href="<?= Url::to(['/admin/page'])?>"><i style="background-image:url(/images/admin/icon_dian.png);"></i>单页管理</a>
                        </dd>
                    </dl>
                </li>


                </li>
                <li>
                     <a <?= $action=='link'||$action=='link-edit'?'class=\'on\'':''?> href="<?= Url::to(['/admin/link'])?>"><i style="background-image:url(/images/admin/icon_link.png);"></i>友情链接</a>
                </li>
            </ul>
        </div>
     
        <div class="pro" style="border-top: 2px solid #2e3344;">

            <div class="hd">
               
                <span class="num">20M</span>
                <span class="name">已用空间</span>
            </div>
           <div class="line"><em style="width:20%"></em></div>
        </div>
    </div>
      
    <div class="edition">V 1.1.2</div>
</div>