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

$this->title = '添加放单';
$this->params['breadcrumbs'][] = ['label' => '放单管理', 'url' => ['/commodity-order/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="modal-header">
        <h4 class="modal-title">添加放单</h4>
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
<div style="border-top: 1px solid #e5e5e5;margin-top: 10px;">&nbsp;</div>
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
                return Html::textInput('CommodityOrderDetail[keyword][]',$data->keyword,['class'=>'form-control']);
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '浏览入口',
            'content'=>function($data){
                $entrances = \app\models\Entrance::find()->all();
                return Html::dropDownList('CommodityOrderDetail[eid][]',$data->eid,ArrayHelper::map($entrances,'id', 'name'),['class'=>'form-control']);
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '卡位条件',
            'content'=>function($data){
                return Html::textInput('CommodityOrderDetail[condition][]',$data->condition,['class'=>'form-control']);
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '笔数',
            'content'=>function($data){
                return Html::textInput('CommodityOrderDetail[num][]',$data->num,['class'=>'form-control']);
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '单价',
            'content'=>function($data){
                return Html::textInput('CommodityOrderDetail[price][]',$data->price,['class'=>'form-control']);
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '佣金',
            'content'=>function($data){
                return Html::textInput('CommodityOrderDetail[fee][]',$data->fee,['class'=>'form-control']);
            }
        ]
    ],
]);?>

    <div class="modal-footer">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end();?>
<?php $this->registerJsFile("/assets/My97DatePicker/WdatePicker.js",['position' => View::POS_HEAD]); ?>