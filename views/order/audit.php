<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/6/1
 * Time: 17:22
 */
use app\models\CommodityOrder;
use app\models\CommodityOrderDetail;
use app\models\Order;
use yii\db\Query;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\LinkPager;

$this->title = '订单审核';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::beginForm(['order/audit'],'post',['id'=>'search_form']) ?>
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
                <?= Html::submitButton('查询',['class' => 'btn-primary pull-left btn'])?>
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
                return '<a href="#" data-type="select" data-name="statu" data-pk="'.$data['id'].'" data-value="'.$data['statu'].'" data-title="'.Order::$Audits[$data['statu']].'">'.Order::$Audits[$data['statu']].'</a>';
            }
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
});');?>
<?php $this->registerJsFile("/assets/My97DatePicker/WdatePicker.js",['position' => View::POS_HEAD]); ?>
<!-- select2 -->
<link href="/assets/select2/css/select2.css" rel="stylesheet">
<script src="/assets/select2/js/select2.js"></script>
<!-- x-editable (bootstrap 3) -->
<link href="/assets/x-editable/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
<script src="/assets/x-editable/bootstrap3-editable/js/bootstrap-editable.js"></script>
<!-- select2 bootstrap -->
<link href="/assets/select2/css/select2-bootstrap.css" rel="stylesheet">
<!-- typeaheadjs -->
<link href="/assets/x-editable/inputs-ext/typeaheadjs/lib/typeahead.js-bootstrap.css" rel="stylesheet">
<script src="/assets/x-editable/inputs-ext/typeaheadjs/lib/typeahead.js"></script>
<script src="/assets/x-editable/inputs-ext/typeaheadjs/typeaheadjs.js"></script>
<script type="text/javascript">
    $(function(){
        $('a[data-name="statu"]').editable({
            source: <?=$audit_json?>,
            url: '/order/update-audit-statu',
            success: function(response, newValue) {
                response = jQuery.parseJSON(response);
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
            },
            validate: function(value) {
                if($.trim(value) == 0) {
                    return '请审核通过或者不通过';
                }
                var ret = window.confirm("确定提交?");
                if(!ret) {
                    return "用户取消操作";
                }
            }
        });
    });
</script>