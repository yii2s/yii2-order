<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/9
 * Time: 16:14
 */
use app\models\Commodity;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = '查看商品';
$this->params['breadcrumbs'][] = ['label' => '商品管理', 'url' => ['/commodity/index']];
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

<?php
//if($model->statu == Commodity::$_AUDIT_ACCESS)
//    echo  $this->context->renderPartial('_view_access',array(
//        'dataProvider' => $dataProvider
//    ));
//else
    echo  $this->context->renderPartial('_view_default',array(
        'dataProvider' => $dataProvider,
        'commodityOrderTemplet' => $commodityOrderTemplet,
        'entrances' => $entrances,
        'entrances_json'=>$entrances_json
    ));