<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/6/3
 * Time: 16:45
 */
use app\models\Log;
use app\models\User;
use yii\grid\GridView;

/* @var $this yii\web\View */
$this->title = '日志记录';
$this->params['breadcrumbs'][] = $this->title;
?>
    <style>
        .gv-table{text-align:center;}
        .gv-table th{text-align:center;}
    </style>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'summary' =>false,
    'emptyText' => false,
    'tableOptions' => ['class'=>'table table-striped table-bordered gv-table'],
    'columns' => [
        'url',
        'method',
        [
            'attribute'=>'模块',
            'value'=>function($data){
                $text = $data['controller'];
                if(!empty(Log::$Controllers[$data['controller']])){
                    $text = Log::$Controllers[$data['controller']];
                }
                return $text;
            }
        ],
        [
            'attribute'=>'动作',
            'value'=>function($data){
                $text = $data['action'];
                if(!empty(Log::$Actions[$data['action']])){
                    $text = Log::$Actions[$data['action']];
                }
                return $text;
            }
        ],
        [
            'attribute'=>'帐号',
            'value'=>function($data){
                $text = '';
                if(!empty($data['uid'])){
                    $user = User::findOne($data['uid']);
                    if(!empty($user))
                        $text = $user->username;
                }
                return $text;
            }
        ],
        'log_time'
    ],
]);
?>