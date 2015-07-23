<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/11
 * Time: 15:09
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = '修改商品';
$this->params['breadcrumbs'][] = ['label' => '商品管理', 'url' => ['/commodity/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modal-header">
    <h4 class="modal-title">修改商品</h4>
</div>
<style>
    .help-block{margin-bottom: 5px;}
</style>
<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data'],
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
            <?= $form->field($model, 'shop_id',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->dropDownList(ArrayHelper::map($shops,'id', 'shop_name'))->label('店铺',['class'=>'control-label col-sm-4']) ?>
        </div>
        <div class="col-sm-6" style="padding-left:0px;">
            <?= $form->field($model, 'platform_id',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->dropDownList(ArrayHelper::map($platforms,'id', 'name'))->label('平台',['class'=>'control-label col-sm-4']) ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-6" style="padding-left:0px;">
            <?= $form->field($model, 'commodity_id',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('ID',['class'=>'control-label col-sm-4']) ?>
        </div>
        <div class="col-sm-6" style="padding-left:0px;">
            <?= $form->field($model, 'sku',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('SKU',['class'=>'control-label col-sm-4']) ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-6" style="padding-left:0px;">
            <?= $form->field($model, 'img',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('商品图',['class'=>'control-label col-sm-4'])->fileInput(['class'=>'form-control']) ?>
        </div>
        <div class="col-sm-6" style="padding-left:0px;">
            <?= $form->field($model, 'rule',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('要求',['class'=>'control-label col-sm-4'])->textarea() ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-6" style="padding-left:0px;">
            <?= $form->field($model, 'credit',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->dropDownList(ArrayHelper::map($this->context->credits,'value', 'text'))->label('买手信用',['class'=>'control-label col-sm-4']) ?>
        </div>
        <div class="col-sm-6" style="padding-left:0px;">
            <?= $form->field($model, 'trade_num',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('最近30天交易不超',['class'=>'control-label col-sm-4']) ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-sm-6" style="padding-left:0px;">
            <?= $form->field($model, 'remark',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('备注',['class'=>'control-label col-sm-4'])->textarea() ?>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="modal-footer">
        <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end();?>