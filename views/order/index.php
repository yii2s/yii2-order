<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/2
 * Time: 17:19
 */
use app\models\CommodityOrder;
use app\models\CommodityOrderDetail;
use app\models\Order;
use yii\db\Query;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\LinkPager;

$this->title = '订单管理';
$this->params['breadcrumbs'][] = $this->title;
?>
    <?= Html::beginForm(['order/index'],'post',['id'=>'search_form']) ?>
    <div>
        <div style="margin-bottom: 5px;">
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'buyer',['class'=>'form-control','placeholder'=>'买家'])?>
            </div>
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'shop',['class'=>'form-control','placeholder'=>'店铺'])?>
            </div>
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'commodity',['class'=>'form-control','placeholder'=>'ID'])?>
            </div>
            <div class="col-sm-1" style="padding-left:0px;">
                <?= Html::button('导出',['class' => 'btn-primary pull-left btn','id'=>'exp'])?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div style="margin-bottom: 5px;">
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'order_no',['class'=>'form-control','placeholder'=>'订单号'])?>
            </div>
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'btime',['class'=>'form-control','placeholder'=>'开始','onfocus'=> Html::decode('WdatePicker({dateFmt:&quot;yyyy-MM-dd HH:mm:ss&quot;,maxDate:&quot;#F{$dp.$D(\'ordersearchform-etime\')}&quot;})')])?>
            </div>
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'etime',['class'=>'form-control','placeholder'=>'结束','onfocus'=> Html::decode('WdatePicker({dateFmt:&quot;yyyy-MM-dd HH:mm:ss&quot;,minDate:&quot;#F{$dp.$D(\'ordersearchform-btime\')}&quot;})')])?>
            </div>
            <div class="col-sm-1" style="padding-left:0px;">
                <?= Html::button('查询',['class' => 'btn-primary pull-left btn','id'=>'search'])?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
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
            'attribute'=>'买家',
            'value'=>function($data){
                return $data['buyer'];
            }
        ],
        [
            'attribute'=>'店铺',
            'value'=>function($data){
                return $data['shop'];
            }
        ],
        [
            'attribute'=>'ID',
            'value'=>function($data){
                return $data['commodity'];
            }
        ],
//        [
//            'class' => 'yii\grid\Column',
//            'header' => '商品图',
//            'content' => function ($data){
//                $html = '';
//                if(!empty($data['img']))
//                    $html = '<img src="'.$data['img'].'" width="60" height="60"/>';
//                return $html;
//            }
//        ],
        [
            'attribute'=>'订单号',
            'value'=>function($data){
                return $data['order_no'];
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header'=>'金额',
            'content'=>function($data){
                $commodityOrderDetail = (new Query())
                    ->select(CommodityOrderDetail::tableName().'.*')
                    ->from(CommodityOrder::tableName())
                    ->leftJoin(CommodityOrderDetail::tableName(),CommodityOrderDetail::tableName().'.coid = '.CommodityOrder::tableName().'.id')
                    ->where('commodity_id = :commodity_id and entrance = :entrance',[':commodity_id'=>$data['commodity'],':entrance'=>$data['entrance']])
                    ->one();
                $style = '';
                if(!is_null($commodityOrderDetail)){
                    if(floatval($commodityOrderDetail['price']) != floatval($data['money']))
                        $style = 'style="color:red;"';
                }
                return '<span '.$style.'>'.$data['money'].'</span>';
            }
        ],
        [
            'attribute'=>'下单时间',
            'value'=>function($data){
                return $data['order_time'];
            }
        ],
        [
            'attribute'=>'收货地址',
            'value'=>function($data){
                if(empty($data['address']))
                    $data['address'] = '';
                return $data['address'];
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header'=>'审核状态',
            'content'=>function($data){
                $color = '#d1d1d1';
                if($data['statu'] == Order::$_AUDIT_ACCESS)
                    $color = 'green';
                else if($data['statu'] == Order::$_AUDIT_NOT_ACCESS)
                    $color = 'red';
                return '<span style="color: '.$color.';">'.Order::$Audits[$data['statu']].'</span>';
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn','header' => '操作',
            'template'=>'{update}&nbsp;{delete}',
            'buttons'=>[
                'update' => function($url,$data,$key){
                    $icon = '<span class="glyphicon glyphicon-pencil"></span>';
                    $html = '<a style="color:#d1d1d1;" title="编辑">'.$icon.'</a>';
                    if($data['statu'] != Order::$_AUDIT_ACCESS)
                        $html = '<a href="update?id='.$data['id'].'" title="编辑">'.$icon.'</a>';
                    return $html;
                },
                'delete' => function ($url,$data,$key){
                    $icon = '<span class="glyphicon glyphicon-trash"></span>';
                    $html = '<a style="color:#d1d1d1;" title="删除">'.$icon.'</a>';
                    if($data['statu'] != Order::$_AUDIT_ACCESS)
                        $html = '<a href="delete?id='.$data['id'].'" title="删除" onclick="return confirm(\'是否删除该订单？\');">'.$icon.'</a>';
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

$("#search").click(function(){
    $("#search_form").attr("action","/order/index");
    $("#search_form").submit();
});

$("#exp").click(function(){
    $("#search_form").attr("action","/order/exportcsv");
    $("#search_form").submit();
});


');?>
<?php $this->registerJsFile("/assets/My97DatePicker/WdatePicker.js",['position' => View::POS_HEAD]); ?>