
<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Param;
$this->title = '首页';


?>


<!-- <div class="col-r"> -->
    <div class="m_user">
        <!-- <span class="st1">到期时间：2020-5-30</span> -->
        <div class="hd">
            <h3><?= Param::getValue('site_name');?></h3>
            <a href="<?= Url::to(['site/index']);?>" class="site "target="_blank"></a>
            <div class="btn">
                <a class="visit" target="_blank" href="<?= Url::to(['site/index']);?>"><i></i><?= Yii::t('common' , '访问');?></a>

            </div>
        </div>
        <ul class="list_info">
            <li>
                <a href="<?= Url::to(['admin/site-param']);?>">
                    <div class="pic">
                        <img src="/images/admin/icon_d4_1.png" alt="" />
                    </div>
                    <h4>设置</h4>
                </a>
            </li>
            <li>
                <a href="<?= Url::to(['admin/product'])?>">
                    <div class="pic">
                        <img src="/images/admin/icon_d6_1.png" alt="" />
                    </div>
                    <h4>产品管理</h4>
                </a>
            </li>
            <li>
                <a href="<?= Url::to(['admin/news'])?>">
                    <div class="pic">
                        <img src="/images/admin/icon_d6_1.png" alt="" />
                    </div>
                    <h4>信息发布</h4>
                </a>
            </li>


        </ul>
    </div>
    <div class="m_statistics">
        <h3 class="tit"><?= Yii::t('common' , '网站访问统计');?></h3>
          
        <div class="content">
            <div class="chart1" id="chart1"></div>
        </div>
    </div>
   <script src="/js/admin/echarts.js"></script>
   <script type="text/javascript">
            var myChart1 = echarts.init(document.getElementById('chart1'));
            myChart1.setOption({
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: <?= $dateArr ?>
                },
                yAxis: {
                    type: 'value',
                    min: 0,
                    max: <?= $countArr['uv']?>,
                    splitNumber: 3
                },
                grid: {
                    left: '3%',
                    top: '10%',
                    right: '3%',
                    bottom: '14%',
                },
                series: [{
                    data: <?= $uvArr;?>,
                    type: 'line',
                    areaStyle: {
                        color: '#bedaf6'
                    },
                    lineStyle: {
                        color: '#7cb5ec'
                    },
                    itemStyle: {
                        borderColor: '#7cb5ec'
                    }
                }]
            });

            // 屏幕改变大小图表跟随改变打小
            window.addEventListener("resize", function() {
                myChart1.resize();
            });
    </script>