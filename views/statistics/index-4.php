<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/6/11
 * Time: 21:14
 */
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\LinkPager;

$this->title = '报表统计';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::beginForm(['statistics/index'],'post',['id'=>'search_form']) ?>
    <div>
        <div style="margin-bottom: 5px;">
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'shop',['class'=>'form-control','placeholder'=>'店铺'])?>
            </div>
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'commodity',['class'=>'form-control','placeholder'=>'商品'])?>
            </div>
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeDropDownList($model, 'platform', ArrayHelper::map($ops,'value', 'text'), ['class' => 'form-control']) ?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div style="margin-bottom: 5px;">
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'btime',['class'=>'form-control','placeholder'=>'开始','onfocus'=> Html::decode('WdatePicker({dateFmt:&quot;yyyy-MM-dd HH:mm:ss&quot;,maxDate:&quot;#F{$dp.$D(\'statisticssearchform-etime\')}&quot;})')])?>
            </div>
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'etime',['class'=>'form-control','placeholder'=>'结束','onfocus'=> Html::decode('WdatePicker({dateFmt:&quot;yyyy-MM-dd HH:mm:ss&quot;,minDate:&quot;#F{$dp.$D(\'statisticssearchform-btime\')}&quot;})')])?>
            </div>
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::button('查询',['class' => 'btn-primary pull-left btn','id'=>'search'])?>
                <?= Html::button('导出',['class' => 'btn-primary pull-left btn','id'=>'exp','style'=>'margin-left:10px;'])?>
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
            'attribute'=>'商品',
            'value'=>function($data){
                return $data['commodity'];
            }
        ],
        [
            'attribute'=>'本金',
            'value'=>function($data){
                return $data['corpus'];
            }
        ],
        [
            'attribute'=>'已收金额',
            'value'=>function($data){
                return $data['total_income'];
            }
        ],
        [
            'attribute'=>'提交笔数',
            'value'=>function($data){
                return $data['total_num'];
            }
        ],
        [
            'attribute'=>'商家笔数',
            'value'=>function($data){
                return $data['budget_num'];
            }
        ],
        [
            'attribute'=>'差额',
            'value'=>function($data){
                return $data['corpus'] - $data['total_income'];
            }
        ],
        [
            'attribute'=>'退款金额',
            'value'=>function($data){
                return $data['real_income'] - $data['total_income'] + $data['fact_fee'];
            }
        ],
        [
            'attribute'=>'执行时间',
            'value'=>function($data){
                return $data['handle_time'];
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
});

$("#search").click(function(){
    $("#search_form").attr("action","/statistics/index");
    $("#search_form").submit();
});

$("#exp").click(function(){
    $("#search_form").attr("action","/statistics/exportcsv");
    $("#search_form").submit();
});

');?>
<?php $this->registerJsFile("/assets/My97DatePicker/WdatePicker.js",['position' => View::POS_HEAD]); ?>