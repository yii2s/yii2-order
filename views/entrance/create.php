<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/4/27
 * Time: 17:21
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">添加入口</h4>
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
    <?= $form->field($model, 'name') ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
    <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end();?>