<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/12
 * Time: 18:15
 */
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

$this->title = '修改放单';
$this->params['breadcrumbs'][] = ['label' => '放单管理', 'url' => ['/commodity-order/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="modal-header">
        <h4 class="modal-title">修改放单</h4>
    </div>
    <style>
        .help-block{margin-bottom: 5px;}
    </style>
<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'fieldConfig' => [
        'template' => "{label}{beginWrapper}{input}{hint}{error}{endWrapper}",
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'offset' => 'col-sm-offset-2',
            'wrapper' => 'col-sm-4',
            'error' => '',
            'hint' => '',
        ],
    ],
]);?>
    <div class="modal-body">
        <input type="hidden" id="commodityorder-cid" class="form-control" name="CommodityOrder[cid]" value="<?= $model->cid; ?>">
        <div class="col-sm-6" style="padding-left:0px;">
            <?= $form->field($model, 'shop',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('店铺',['class'=>'control-label col-sm-4'])->textInput(['readonly'=>'readonly']) ?>
            <?= $form->field($model, 'platform',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('平台',['class'=>'control-label col-sm-4'])->textInput(['readonly'=>'readonly']) ?>
            <?= $form->field($model, 'commodity_id',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('ID',['class'=>'control-label col-sm-4'])->textInput(['readonly'=>'readonly']) ?>
            <?= $form->field($model, 'sku',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('SKU',['class'=>'control-label col-sm-4'])->textInput() ?>
            <?= $form->field($model, 'handle_time',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('执行时间',['class'=>'control-label col-sm-4'])->textInput(['onfocus'=>Html::decode('WdatePicker({dateFmt:&quot;yyyy-MM-dd HH:mm:ss&quot;,minDate:&quot;%y-%M-%d %H:%m:%s&quot;})')]) ?>
            <?= $form->field($model, 'rule',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('要求',['class'=>'control-label col-sm-4'])->textarea() ?>
            <?= $form->field($model, 'credit',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->dropDownList(ArrayHelper::map($this->context->credits,'value', 'text'))->label('买手信用',['class'=>'control-label col-sm-4']) ?>
            <?= $form->field($model, 'trade_num',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('最近30天交易不超',['class'=>'control-label col-sm-4']) ?>
            <?= $form->field($model, 'remark',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('备注',['class'=>'control-label col-sm-4'])->textarea() ?>
        </div>
        <div class="col-sm-6" style="padding-left:0px;">
            <div class="form-group field-commodityorder-img">
                <label class="control-label col-sm-4" for="commodityorder-img">商品图</label>
                <div class="col-sm-8">
                    <img src="<?= $model->img; ?>" style="max-width: 360px; max-height: 360px;"/>
                    <input type="hidden" id="commodityorder-img" class="form-control" name="CommodityOrder[img]" value="<?= $model->img; ?>">
                    <div class="help-block help-block-error "></div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

<div class="modal-footer">
    <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end();?>
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
                return '<a href="#" data-type="text" data-name="keyword" data-pk="'.$data['id'].'" data-value="'.$data['keyword'].'" data-title="关键词">'.$data['keyword'].'</a>';
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '浏览入口',
            'content'=>function($data){
                return '<a href="#" data-type="select" data-name="eid" data-pk="'.$data['id'].'" data-value="'.$data['eid'].'" data-title="浏览入口">'.$data['entrance'].'</a>';
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '卡位条件',
            'content'=>function($data){
                return '<a href="#" data-type="text" data-name="condition" data-pk="'.$data['id'].'" data-value="'.$data['condition'].'" data-title="卡位条件">'.$data['condition'].'</a>';
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '笔数',
            'content'=>function($data){
                return '<a href="#" data-type="text" data-name="num" data-pk="'.$data['id'].'" data-value="'.$data['num'].'" data-title="笔数">'.$data['num'].'</a>';
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '单价',
            'content'=>function($data){
                return '<a href="#" data-type="text" data-name="price" data-pk="'.$data['id'].'" data-value="'.$data['price'].'" data-title="单价">'.$data['price'].'</a>';
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '佣金',
            'content'=>function($data){
                return '<a href="#" data-type="text" data-name="fee" data-pk="'.$data['id'].'" data-value="'.$data['fee'].'" data-title="佣金">'.$data['fee'].'</a>';
            }
        ]
    ],
]);?>
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
        $('a[data-name="keyword"]').editable({
            url: '/commodity-order/update-order-detail',
            success: function(response, newValue) {
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
            }
        });
        $('a[data-name="eid"]').editable({
            source: <?=$entrances_json?>,
            url: '/commodity-order/update-order-detail',
            success: function(response, newValue) {
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
            }
        });
        $('a[data-name="condition"]').editable({
            url: '/commodity-order/update-order-detail',
            success: function(response, newValue) {
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
            }
        });
        $('a[data-name="num"]').editable({
            url: '/commodity-order/update-order-detail',
            success: function(response, newValue) {
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
            }
        });
        $('a[data-name="price"]').editable({
            url: '/commodity-order/update-order-detail',
            success: function(response, newValue) {
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
            }
        });
        $('a[data-name="fee"]').editable({
            url: '/commodity-order/update-order-detail',
            success: function(response, newValue) {
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
            }
        });
    });
</script>