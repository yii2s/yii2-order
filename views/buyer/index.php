<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/2
 * Time: 17:19
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = '小号管理';
$this->params['breadcrumbs'][] = $this->title;
?>
    <?= Html::beginForm(['buyer/index'],'post',['id'=>'search_form']) ?>
    <div style="margin-bottom: 5px;">
        <div>
            <div class="col-sm-2" style="padding-left:0px;">
                <select id="buyer-platform" class="form-control" name="Buyer[platform]">
                    <option value="" <?= $model->platform==''?'selected':''?>>全部</option>
                    <?php
                    foreach($platforms as $platform){
                        ?>
                        <option value="<?=$platform->name;?>" <?= $model->platform==$platform->name?'selected':''?>><?=$platform->name;?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-2" style="padding-left:0px;">
            <?= Html::activeTextInput($model,'account',['class'=>'form-control','placeholder'=>'帐号'])?>
            </div>
            <div class="col-sm-1" style="padding-left:0px;padding-right:0px;margin-top: 5px;">
                <?= Html::activeCheckbox($model,'isBuy')?>
            </div>
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::button('查询',['class' => 'btn-primary pull-left btn','id'=>'search'])?>
                <?= Html::button('导出',['class' => 'btn-primary pull-left btn','id'=>'exp','style'=>'margin-left:10px;'])?>
            </div>
        </div>
        <?php
        Modal::begin([
            'toggleButton' => ['label' => '导入','class' => 'btn-primary pull-right btn','style' => 'margin-left:10px;'],
            'clientOptions' => ['remote'=>'/buyer/import'],
        ]);
        Modal::end();
        Modal::begin([
            'toggleButton' => ['label' => '添加','class' => 'btn-primary pull-right btn'],
            'clientOptions' => ['remote'=>'/buyer/create'],
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
        ['attribute'=>'帐号','value'=>function($data){return $data['account'];}],
        ['attribute'=>'平台','value'=>function($data){return $data['platform'];}],
        ['attribute'=>'信用等级','value'=>function($data){return $data['credit'];}],
        ['attribute'=>'月','value'=>function($data){return $data['month'];}],
        ['attribute'=>'周','value'=>function($data){return $data['week'];}],
        ['attribute'=>'注册时间','value'=>function($data){return $data['regtime'];}],
        ['attribute'=>'添加时间','value'=>function($data){return $data['create_time'];}],
        [
            'class' => 'yii\grid\ActionColumn','header' => '操作',
            'template'=>'{view}&nbsp;{delete}',
            'buttons'=>[
                'view' => function($url,$data,$key){
                    $icon = '<span class="glyphicon glyphicon-eye-open"></span>';
                    $html = '<a href="view?id='.$data['id'].'" title="查看">'.$icon.'</a>';
                    return $html;
                },
                'delete' => function ($url,$data,$key){
                    $icon = '<span class="glyphicon glyphicon-trash"></span>';
                    $html = '<a href="delete?id='.$data['id'].'" onclick="return confirm(\'是否删除该小号？\');">'.$icon.'</a>';
                    return $html;
                }
            ],
        ]
    ],
]);
?>
<?= LinkPager::widget(['pagination' => $pages]); ?>
<?= Html::endForm() ?>
<?php Yii::$app->view->registerJs('
$("ul.pagination li a").click(function () {
var page = $(this).attr(\'href\');
$("#search_form").action(page);
$("#search_form").submit();
return false;
});

$("#search").click(function(){
    $("#search_form").attr("action","/buyer/index");
    $("#search_form").submit();
});

$("#exp").click(function(){
    $("#search_form").attr("action","/buyer/exportcsv");
    $("#search_form").submit();
});
');?>