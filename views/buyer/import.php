<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/2
 * Time: 23:24
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">导入小号</h4>
    </div>
    <style>
        .form-group{margin-bottom: 0px;}
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
        <?= $form->field($model, 'file')->fileInput() ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end();?>