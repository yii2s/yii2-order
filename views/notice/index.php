<?php
use yii\bootstrap\Button;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = '公告管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div style="margin-bottom: 5px;">
    <?= Html::tag('a','添加',['href'=>'/notice/create','class'=>'btn-primary pull-right btn']) ?>
    <div class="clearfix"></div>
</div>
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
        //['class' => 'yii\grid\SerialColumn'],
        //显示的字段
        //code的值
        //['attribute'=>'这是测试code','value'=>function(){return 'abc';}],
        'id',
        'title',
        'create_time',
        [
            'class' => 'yii\grid\ActionColumn','header' => '操作',
            'template'=>'{update}&nbsp;{delete}',
            'buttons'=>[
                'update' => function($url,$data,$key){
                    $icon = '<span class="glyphicon glyphicon-pencil"></span>';
                    $html = '<a href="update?id='.$data['id'].'" title="编辑">'.$icon.'</a>';
                    return $html;
                },
                'delete' => function ($url,$model,$key){
                    $icon = '<span class="glyphicon glyphicon-trash"></span>';
                    $html = '<a href="'.$url.'" onclick="return confirm(\'是否删除该公告？\');">'.$icon.'</a>';
                    return $html;
                }
            ],
        ]
    ],
]);
?>