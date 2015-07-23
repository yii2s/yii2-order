<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/6/3
 * Time: 17:43
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
?>
    <div class="modal-header">
        <h4 class="modal-title">修改密码</h4>
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
        <?= $form->field($model, 'oldpassword')->passwordInput() ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'repassword')->passwordInput() ?>
    </div>
    <div class="modal-footer">
        <?= Html::submitButton('修改', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end();?>