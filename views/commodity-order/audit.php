<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/28
 * Time: 0:26
 */
use app\models\CommodityOrder;
use app\models\Statistics;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\LinkPager;

$this->title = '放单审核';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::beginForm(['commodity-order/audit'],'post',['id'=>'search_form']) ?>
    <div>
        <div style="margin-bottom: 5px;">
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'shop',['class'=>'form-control','placeholder'=>'店铺'])?>
            </div>
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'commodity',['class'=>'form-control','placeholder'=>'ID'])?>
            </div>
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'sku',['class'=>'form-control','placeholder'=>'SKU'])?>
            </div>
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeDropDownList($model, 'statu', ArrayHelper::map($ops,'value', 'text'), ['class' => 'form-control']) ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div style="margin-bottom: 5px;">
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'entrance',['class'=>'form-control','placeholder'=>'浏览入口'])?>
            </div>
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'btime',['class'=>'form-control','placeholder'=>'开始','onfocus'=> Html::decode('WdatePicker({dateFmt:&quot;yyyy-MM-dd HH:mm:ss&quot;,maxDate:&quot;#F{$dp.$D(\'commoditysearchform-etime\')}&quot;})')])?>
            </div>
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'etime',['class'=>'form-control','placeholder'=>'结束','onfocus'=> Html::decode('WdatePicker({dateFmt:&quot;yyyy-MM-dd HH:mm:ss&quot;,minDate:&quot;#F{$dp.$D(\'commoditysearchform-btime\')}&quot;})')])?>
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
            'attribute'=>'店铺',
            'value'=>function($data){
                return $data['shop'];
            }
        ],
        [
            'attribute'=>'平台',
            'value'=>function($data){
                return $data['platform'];
            }
        ],
        [
            'attribute'=>'ID',
            'value'=>function($data){
                return $data['commodity_id'];
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
            'attribute'=>'SKU',
            'value'=>function($data){
                return $data['sku'];
            }
        ],
        [
            'attribute'=>'要求',
            'value'=>function($data){
                return $data['rule'];
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '买手信用',
            'content' => function ($data){
                if(empty($data['credit']))
                    $data['credit'] = '';
                else{
                    foreach($this->context->credits as $credit){
                        if($credit['value'] == $data['credit'])
                            $data['credit'] = $credit['text'];
                    }
                }
                return $data['credit'];
            }
        ],
        [
            'attribute'=>'添加时间',
            'value'=>function($data){
                return $data['create_time'];
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header'=>'审核状态',
            'content'=>function($data){
                $html = CommodityOrder::$Audits[$data['statu']];
                if($data['statu'] == CommodityOrder::$_AUDIT_PEND)
                    $html = '<a href="#" data-type="select" data-name="statu" data-pk="'.$data['id'].'" data-value="'.$data['statu'].'" data-title="审核状态">'.CommodityOrder::$Audits[$data['statu']].'</a>';
                return $html;
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header'=>'实收金额',
            'content'=>function($data){
                if(empty($data['real_income']))
                    $data['real_income'] = '0.00';
                $statistics = Statistics::find()->where('coid = :coid',[':coid'=>$data['id']])->one();
                $style = '';
                if(!empty($statistics)){
                    $total = $statistics->corpus + $statistics->total_fee;
                    if($data['real_income'] != '0.00' && $data['real_income'] <  $total){
                        $style = 'style="color:red;"';
                    }
                }
                return '<a href="#" '.$style.' data-type="text" data-name="real_income" data-pk="'.$data['id'].'" data-value="'.$data['real_income'].'" data-title="实收金额">'.$data['real_income'].'</a>';
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn','header' => '操作',
            'template'=>'{allot}&nbsp;{view}',
            'buttons'=>[
                'allot' => function ($url,$data,$key){
                    $icon = '<span class="glyphicon glyphicon-user"></span>';
                    $html = '<a style="color:#d1d1d1;" title="分配">'.$icon.'</a>';
                    if($data['statu'] == CommodityOrder::$_AUDIT_ACCESS)
                        $html = '<a href="allot?id='.$data['id'].'" title="分配">'.$icon.'</a>';
                    return $html;
                },
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
');?>
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
            url: '/commodity-order/update-audit-statu',
            success: function(response, newValue) {
                response = jQuery.parseJSON(response);
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
                location.reload(true);
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

        $('a[data-name="real_income"]').editable({
            url: '/commodity-order/update-real-income',
            success: function(response, newValue) {
                response = jQuery.parseJSON(response);
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
                location.reload(true);
            }
        });
    });
</script>