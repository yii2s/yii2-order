<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/6/4
 * Time: 21:15
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = '修改公告';
$this->params['breadcrumbs'][] = ['label' => '公告管理', 'url' => ['/notice/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="modal-header">
        <h4 class="modal-title">修改公告</h4>
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
        <?= $form->field($model, 'title') ?>
        <?= $form->field($model, 'content')->textarea(['rows'=>'10']) ?>
        <?= $form->field($model, 'rids')->checkboxList(ArrayHelper::map($roles,'id', 'name')) ?>
    </div>
    <div class="modal-footer">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end();?>