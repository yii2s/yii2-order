<?php
use yii\bootstrap\Modal;
use yii\grid\GridView;

/* @var $this yii\web\View */
$this->title = '入口管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div style="margin-bottom: 5px;">
<?php
Modal::begin([
    'toggleButton' => ['label' => '添加','class' => 'btn-primary pull-right btn'],
    'clientOptions' => ['remote'=>'/entrance/create'],
]);
Modal::end();
?>
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
        'name',
        'create_time',
        [
            'class' => 'yii\grid\ActionColumn','header' => '操作',
            'template'=>'{update}&nbsp;{delete}',
            'buttons'=>[
                'update' => function ($url, $model, $key) {
                    Yii::$app->view->registerJs('jQuery("#update'.$model->id.'").modal({"show":false,"remote":"'.$url.'"});');
                    $model_html = '<div id="update'.$model->id.'" class="fade modal" role="dialog" tabindex="-1"><div class="modal-dialog "><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div><div class="modal-body"></div></div></div></div>';//'<script>jQuery("#update'.$model->id.'").modal({"show":false,"remote":"'.$url.'"});</script>';
                    $html = '<a data-toggle="modal" data-target="#update'.$model->id.'" href="javascript:;" alt="修改"><span class="glyphicon glyphicon-pencil"></span></a>';
                    return $html.$model_html;
                },
                'delete' => function ($url,$model,$key){
                    $icon = '<span class="glyphicon glyphicon-trash"></span>';
                    $html = '<a href="'.$url.'" onclick="return confirm(\'是否删除该平台？\');">'.$icon.'</a>';
                    return $html;
                }
            ],
        ]
    ],
]);
?>