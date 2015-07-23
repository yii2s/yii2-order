<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/2
 * Time: 17:24
 */
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

$this->title = '提交订单';
$this->params['breadcrumbs'][] = ['label' => '订单管理', 'url' => ['/order/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="modal-header">
        <h4 class="modal-title">提交订单</h4>
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
        <div class="col-sm-8" style="padding-left:0px;">
                <?= $form->field($model, 'buyer',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('买家',['class'=>'control-label col-sm-4'])->textInput(['style'=>'width:90%;float:left;'])->hint("&nbsp;*",['style'=>'float:left;color:red;font-size:25px;'])->error(['style'=>'clear:both;']) ?>
                <?= $form->field($model, 'commodity',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('ID',['class'=>'control-label col-sm-4'])->textInput(['style'=>'width:90%;float:left;'])->hint("&nbsp;*",['style'=>'float:left;color:red;font-size:25px;'])->error(['style'=>'clear:both;']) ?>
                <?= $form->field($model, 'entrance',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('浏览入口',['class'=>'control-label col-sm-4'])->textInput(['style'=>'width:90%;float:left;'])->hint("&nbsp;*",['style'=>'float:left;color:red;font-size:25px;'])->error(['style'=>'clear:both;']) ?>
                <?= $form->field($model, 'order_no',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('订单号',['class'=>'control-label col-sm-4'])->textInput(['style'=>'width:90%;float:left;'])->hint("&nbsp;*",['style'=>'float:left;color:red;font-size:25px;'])->error(['style'=>'clear:both;']) ?>
                <?= $form->field($model, 'money',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('金额',['class'=>'control-label col-sm-4'])->textInput(['style'=>'width:90%;float:left;'])->hint("&nbsp;*",['style'=>'float:left;color:red;font-size:25px;'])->error(['style'=>'clear:both;']) ?>
                <?= $form->field($model, 'order_time',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('下单时间',['class'=>'control-label col-sm-4'])->textInput(['onfocus'=>Html::decode('WdatePicker({dateFmt:&quot;yyyy-MM-dd HH:mm:ss&quot;})')]) ?>
                <?= $form->field($model, 'address',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('收货地址',['class'=>'control-label col-sm-4'])->textarea() ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="modal-footer">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end();?>
<?php $this->registerJsFile("/assets/My97DatePicker/WdatePicker.js",['position' => View::POS_HEAD]); ?>