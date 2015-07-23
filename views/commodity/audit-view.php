<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/27
 * Time: 21:35
 */
use yii\grid\GridView;
use yii\widgets\DetailView;

$this->title = '查看商品';
$this->params['breadcrumbs'][] = ['label' => '商品审核', 'url' => ['/commodity/audit']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    .box01 li{margin:0;padding:0;list-style-type:none;float:left;width:180px;text-align:center;padding-right:10px;margin-right:10px;height:120px;margin-bottom:15px;cursor:pointer;z-index:0;position:relative;}
    .box01 li img{height:120px;margin:0 auto;}
    .box01 li .in{position:absolute;left:0;top:0;}
    .box01 li .in p{display:none;text-align:left;}
    .box01 li.on{z-index:99;}
    .box01 li.on .in{padding:5px;border:1px solid #ccc;position:absolute;z-index:100;width:auto;text-align:center;top:-40px; background:#fff;}
    .box01 li.on .in p{position:relative;display:block;}
    .box01 li.on img{height:auto;margin-bottom:8px;}
</style>
<script type="text/javascript">
    $(function(){
        $('.imgli').hover(function() {
                $(this).addClass('on');
                var wl = $(this).find('img').attr('width');
                if (wl < 190) {
                    $(this).find('.in').css('left', '0')
                } else {
                    $(this).find('.in').css('left', -wl / 4)
                }
            },
            function() {
                $(this).animate({
                        height: "120px"
                    },
                    100).removeClass('on');
                $(this).find('.in').css('left', '0')
            });
    })
</script>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'shop',
        'platform',
        'commodity_id',
        'sku',
        [
            'label' => '商品图',
            'value' => '<ul class="box01" style="padding-left: 0px;"><li class="imgli"><div class="in"><a href="#" target="_blank"><img src="'.$model->img.'"/></a></div></li></ul>',
            'format' => 'html'
        ],
        'rule',
        'credit',
        [
            'label' => '最近30天交易不超',
            'value' => $model->trade_num.'笔',
        ],
        'remark',
        'create_time',
        'uid'
    ],
]);?>
<style>
    .gv-table{text-align:center;}
    .gv-table th{text-align:center;}
</style>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'summary' =>false,
    'emptyText' => '无关键词',
    'tableOptions' => ['class'=>'table table-striped table-bordered gv-table'],
    'columns' => [
        [
            'class' => 'yii\grid\Column',
            'header' => '关键词',
            'content'=>function($data){
                return $data['keyword'];
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '浏览入口',
            'content'=>function($data){
                return $data['entrance'];
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '卡位条件',
            'content'=>function($data){
                return $data['condition'];
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '笔数',
            'content'=>function($data){
                return $data['num'];
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '单价',
            'content'=>function($data){
                return $data['price'];
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '佣金',
            'content'=>function($data){
                return $data['fee'];
            }
        ]
    ],
]);?>
<div class="modal-footer">
    <button id="backButton" type="button" class="btn btn-default" onclick="history.go(-1);">返回</button>
</div>