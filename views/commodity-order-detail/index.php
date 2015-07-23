<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/6/3
 * Time: 15:33
 */
use app\models\Buyer;
use app\models\CommodityOrder;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\LinkPager;

$this->title = '业务接单';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::beginForm(['commodity-order-detail/index'],'post',['id'=>'search_form']) ?>
<style>
    .gv-table{text-align:center;}
    .gv-table th{text-align:center;}
</style>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'summary' =>false,
    'emptyText' => false,
    'tableOptions' => ['class'=>'table table-striped table-bordered gv-table'],
    'columns' => [
        [
            'attribute'=>'店铺',
            'value'=>function($data){
                $commodityOrder = CommodityOrder::findOne($data['coid']);
                return $commodityOrder->shop;
            }
        ],
        [
            'attribute'=>'平台',
            'value'=>function($data){
                $commodityOrder = CommodityOrder::findOne($data['coid']);
                return $commodityOrder->platform;
            }
        ],
        [
            'attribute'=>'ID',
            'value'=>function($data){
                $commodityOrder = CommodityOrder::findOne($data['coid']);
                return $commodityOrder->commodity_id;
            }
        ],
        [
            'attribute'=>'SKU',
            'value'=>function($data){
                $commodityOrder = CommodityOrder::findOne($data['coid']);
                return $commodityOrder->sku;
            }
        ],
        [
            'attribute'=>'要求',
            'value'=>function($data){
                $commodityOrder = CommodityOrder::findOne($data['coid']);
                if(empty($commodityOrder->rule))
                    $commodityOrder->rule = '';
                return $commodityOrder->rule;
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header'=>'买手信用',
            'content'=>function($data){
                $commodityOrder = CommodityOrder::findOne($data['coid']);
                if(empty($commodityOrder->credit))
                    $commodityOrder->credit = '';
                return '<span id="credit'.$data['id'].'">'.$commodityOrder->credit.'</span>';
            }
        ],
        [
            'attribute'=>'最近30天交易不超',
            'value'=>function($data){
                $commodityOrder = CommodityOrder::findOne($data['coid']);
                if(empty($commodityOrder->trade_num))
                    $commodityOrder->trade_num = '';
                return $commodityOrder->trade_num;
            }
        ],
        [
            'attribute'=>'关键词',
            'value'=>function($data){
                return $data['keyword'];
            }
        ],
        [
            'attribute'=>'浏览入口',
            'value'=>function($data){
                return $data['entrance'];
            }
        ],
        [
            'attribute'=>'卡位条件',
            'value'=>function($data){
                if(empty($data['condition']))
                    $data['condition'] = '';
                return $data['condition'];
            }
        ],
        [
            'attribute'=>'笔数',
            'value'=>function($data){
                return $data['num'];
            }
        ],
        [
            'attribute'=>'单价',
            'value'=>function($data){
                return $data['price'];
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header'=>'推荐',
            'content'=>function($data){
                $result = '';
                if(!empty($data['recommends'])){
                    $buyers = Buyer::find()->where('id in (:id)',[':id'=>$data['recommends']])->all();
                    foreach($buyers as $buyer){
                        $result .= '['.$buyer->account.','.$buyer->pass.']<br/>';
                    }
                }
                return $result;
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn','header' => '操作',
            'template'=>'{view}',
            'buttons'=>[
                'view' => function($url,$data,$key){
                    $icon = '<span class="glyphicon glyphicon-eye-open"></span>';
                    $html = '<a href="view?id='.$data['id'].'" title="查看">'.$icon.'</a>';
                    return $html;
                }
            ],
        ]
    ],
]);
?>
<?= LinkPager::widget(['pagination' => $pages]); ?>
<?= Html::endForm() ?>
<?php Yii::$app->view->registerJs('
$("ul.pagination li a").click(function () {
var page = $(this).attr(\'href\');
$("#search_form").action(page);
$("#search_form").submit();
return false;
});
$("td span").each(function(){
        gStart(this.id);
    });
');?>
<script type="text/javascript">
    //信用图
    var pfarry = [
        'b_red_',
        'b_blue_',
        'b_cap_',
        'b_crown_'
    ];
    function gStart(id){
        var start = $("#"+id).text();
        var ary = start.split('－');
        var fen = ary[0];

        var imgDir = '/assets/images/level/';
        var src = '';
        if(fen >= 4 && fen <= 10){
            src = pfarry[0]+'1.gif';
        }else if(fen >= 11 && fen <= 40){
            src = pfarry[0]+'2.gif';
        }else if(fen >= 41 && fen <= 90){
            src = pfarry[0]+'3.gif';
        }else if(fen >= 91 && fen <= 150){
            src = pfarry[0]+'4.gif';
        }else if(fen >= 151 && fen <= 250){
            src = pfarry[0]+'5.gif';
        }else if(fen >= 251 && fen <= 500){
            src = pfarry[1]+'1.gif';
        }else if(fen >= 501 && fen <= 1000){
            src = pfarry[1]+'2.gif';
        }else if(fen >= 1001 && fen <= 2000){
            src = pfarry[1]+'3.gif';
        }else if(fen >= 2001 && fen <= 5000){
            src = pfarry[1]+'4.gif';
        }else if(fen >= 5001 && fen <= 10000){
            src = pfarry[1]+'5.gif';
        }else if(fen >= 10001 && fen <= 20000){
            src = pfarry[2]+'1.gif';
        }else if(fen >= 20001 && fen <= 50000){
            src = pfarry[2]+'2.gif';
        }else if(fen >= 50001 && fen <= 100000){
            src = pfarry[2]+'3.gif';
        }else if(fen >= 100001 && fen <= 200000){
            src = pfarry[2]+'4.gif';
        }else if(fen >= 200001 && fen <= 500000){
            src = pfarry[2]+'5.gif';
        }else if(fen >= 500001 && fen <= 1000000){
            src = pfarry[3]+'1.gif';
        }else if(fen >= 1000001 && fen <= 2000000){
            src = pfarry[3]+'2.gif';
        }else if(fen >= 2000001 && fen <= 5000000){
            src = pfarry[3]+'3.gif';
        }else if(fen >= 5000001 && fen <= 10000000){
            src = pfarry[3]+'4.gif';
        }else if(fen >= 10000001){
            src = pfarry[3]+'5.gif';
        }else{
            src = '';
        }
        if(src != ''){
            //var fenHtml = start+'点 <img alt="'+start+'" src="'+imgDir+src+'"/>';
            var fenHtml = '<img alt="'+start+'" src="'+imgDir+src+'"/>';
            $("#"+id).html(fenHtml);
        }
    }
</script>