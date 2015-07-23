<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/27
 * Time: 23:34
 */
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>
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
        ],
        [
            'class' => 'yii\grid\ActionColumn','header' => '操作',
            'template'=>'{delete}',
            'buttons'=>[
                'delete' => function ($url,$data,$key){
                    $icon = '<span class="glyphicon glyphicon-trash"></span>';
                    $html = '<a href="/commodity/delete-order-templet?id='.$data['id'].'&cid='.$data['cid'].'" title="删除" onclick="return confirm(\'是否删除？\');">'.$icon.'</a>';
                    return $html;
                }
            ],
        ]
    ],
]);?>

<div class="modal-header">
    <h4 class="modal-title">添加</h4>
</div>
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
    <div class="col-sm-6" style="padding-left:0px;">
        <?= $form->field($commodityOrderTemplet, 'keyword',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('关键词',['class'=>'control-label col-sm-4']) ?>
    </div>
    <div class="col-sm-6" style="padding-left:0px;">
        <?= $form->field($commodityOrderTemplet, 'eid',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->dropDownList(ArrayHelper::map($entrances,'id', 'name'))->label('浏览入口',['class'=>'control-label col-sm-4']) ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-6" style="padding-left:0px;">
        <?= $form->field($commodityOrderTemplet, 'condition',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('卡位条件',['class'=>'control-label col-sm-4']) ?>
    </div>
    <div class="col-sm-6" style="padding-left:0px;">
        <?= $form->field($commodityOrderTemplet, 'num',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('笔数',['class'=>'control-label col-sm-4']) ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-6" style="padding-left:0px;">
        <?= $form->field($commodityOrderTemplet, 'price',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('单价',['class'=>'control-label col-sm-4']) ?>
    </div>
    <div class="col-sm-6" style="padding-left:0px;">
        <?= $form->field($commodityOrderTemplet, 'fee',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('佣金',['class'=>'control-label col-sm-4']) ?>
    </div>
    <div class="clearfix"></div>
</div>
<div class="modal-footer">
    <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end();?>
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
            url: '/commodity/update-order-templet',
            success: function(response, newValue) {
                response = jQuery.parseJSON(response);
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
            }
        });
        $('a[data-name="eid"]').editable({
            source: <?=$entrances_json?>,
            url: '/commodity/update-order-templet',
            success: function(response, newValue) {
                response = jQuery.parseJSON(response);
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
            }
        });
        $('a[data-name="condition"]').editable({
            url: '/commodity/update-order-templet',
            success: function(response, newValue) {
                response = jQuery.parseJSON(response);
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
            }
        });
        $('a[data-name="num"]').editable({
            url: '/commodity/update-order-templet',
            success: function(response, newValue) {
                response = jQuery.parseJSON(response);
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
            }
        });
        $('a[data-name="price"]').editable({
            url: '/commodity/update-order-templet',
            success: function(response, newValue) {
                response = jQuery.parseJSON(response);
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
            }
        });
        $('a[data-name="fee"]').editable({
            url: '/commodity/update-order-templet',
            success: function(response, newValue) {
                response = jQuery.parseJSON(response);
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
            }
        });
    });
</script>