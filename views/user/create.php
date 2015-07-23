<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/4/27
 * Time: 17:21
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = '添加帐号';
$this->params['breadcrumbs'][] = ['label' => '帐号管理', 'url' => ['/user/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modal-header">
    <h4 class="modal-title">添加帐号</h4>
</div>
<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'fieldConfig' => [
        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
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
    <?= $form->field($model, 'username') ?>
    <?= $form->field($model, 'password') ?>
    <?= $form->field($model, 'rid')->dropDownList(ArrayHelper::map($roles,'id', 'name')) ?>
    <?= $form->field($model, 'remark') ?>
</div>
<div class="modal-footer">
    <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end();?>