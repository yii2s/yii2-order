<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/6/7
 * Time: 22:46
 */
use app\controllers\CommodityController;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

$this->title = '查看';
$this->params['breadcrumbs'][] = ['label' => '业务接单', 'url' => ['/commodity-order-detail/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modal-header">
    <h4 class="modal-title">查看</h4>
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
        <?= $form->field($model, 'sku',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('SKU',['class'=>'control-label col-sm-4'])->textInput(['readonly'=>'readonly']) ?>
        <?= $form->field($model, 'rule',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('要求',['class'=>'control-label col-sm-4'])->textarea(['readonly'=>'readonly']) ?>
        <?= $form->field($model, 'credit',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->dropDownList(ArrayHelper::map($this->context->credits,'value', 'text'),['readonly'=>'readonly','disabled'=>'disabled'])->label('买手信用',['class'=>'control-label col-sm-4']) ?>
        <?= $form->field($model, 'trade_num',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('最近30天交易不超',['class'=>'control-label col-sm-4'])->textInput(['readonly'=>'readonly']) ?>
        <?= $form->field($model, 'remark',['horizontalCssClasses' => ['wrapper' => 'col-sm-8']])->label('备注',['class'=>'control-label col-sm-4'])->textarea(['readonly'=>'readonly']) ?>
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