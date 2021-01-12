<?php
use app\components\Helper;
$this->title = '访问统计';
?>

<div class="m_statistics" style="margin-top: 20px">
    <h3 class="tit">网站访问统计</h3>
    <div class="content">
        <div class="chart1" id="chart1"></div>
    </div>
</div>

<div class="container-statdata">
    <h3 class="tit">24小时访统计</h3>
    <div class="content">
        <table>
            <thead>
            <tr>
                <th class="date">时间</th>
                <th class="uv">访问人数（IP）</th>
                <th class="pv">访问次数（PV）</th>
                <th class="proportion">比例</th>
            </tr>
            </thead>
            <!--计算公式，当前小时人数除以24小时总访问人数（IP），得出的百分比-->
            <tbody>
               <?php $all = 0;foreach($OneDayAccessArr as $k=>$v){$all+=$v['ip'];}?>

                <?php foreach($OneDayAccessArr as $k=>$v):?>
                    <?php  $percent = Helper::percentage($v['ip'],$all)?>
                <tr>
                    <td class="date"><?= $k?></td>
                    <td class="uv"><?= $v['ip']?></td>
                    <td class="pv"><?= $v['pv']?></td>
                    <td class="proportion">
                    <div class="percent-bar"><div class="orange" style="width:<?= $percent?>;display:block"></div></div><?= $percent?>
                    </td>
                </tr>
                <?php endforeach;?>
               
            </tbody>
        </table>
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


