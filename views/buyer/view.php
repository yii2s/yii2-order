<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/6/9
 * Time: 10:17
 */
use yii\widgets\DetailView;

$this->title = '查看小号';
$this->params['breadcrumbs'][] = ['label' => '小号管理', 'url' => ['/buyer/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'platform',
        'account',
        'pass',
        'pay_account',
        'pay_pass',
        'regphone',
        'regtime',
        'regarea',
        'credit',
        'id_verifi',
        'address',
        'weight',
        'remark',
        'buy_time',
        'create_time'
    ],
]);?>