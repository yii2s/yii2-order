<?php
use app\models\Role;
use yii\bootstrap\Button;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = '帐号管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div style="margin-bottom: 5px;">
    <?= Html::tag('a','添加',['href'=>'/user/create','class'=>'btn-primary pull-right btn']) ?>
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
        'username',
        [
            'class' => 'yii\grid\Column',
            'header' => '角色',
            'content' => function ($data){
                $role_name = '';
                $role = Role::find()->where('id = :id',[':id'=>$data['rid']])->one();
                if(!empty($role))
                    $role_name = $role->name;
                return $role_name;
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '备注',
            'content' => function ($data){
                if(empty($data['remark']))
                    return '';
                return $data['remark'];
            }
        ],
        'create_time',
        [
            'class' => 'yii\grid\ActionColumn','header' => '操作',
            'template'=>'{allot}&nbsp;{update}&nbsp;{delete}&nbsp;{power}',
            'buttons'=>[
                'allot' => function($url,$data,$key){
                    $icon = '<span class="glyphicon glyphicon-th"></span>';
                    $html = '<a style="color:#d1d1d1;" title="分配店铺">'.$icon.'</a>';
                    if($data['rid'] == 3)
                        $html = '<a href="/shop/allot?uid='.$data['id'].'" title="分配店铺">'.$icon.'</a>';
                    return $html;
                },
                'update' => function($url,$data,$key){
                    $result = '';
                    $icon = '<span class="glyphicon glyphicon-pencil"></span>';
                    if($this->context->user->rid > 1 && $data['rid'] == 2){
                        $result = '<a style="color:#d1d1d1;" title="编辑">'.$icon.'</a>';
                    }else{
                        Yii::$app->view->registerJs('jQuery("#update'.$data['id'].'").modal({"show":false,"remote":"/user/update?id='.$data['id'].'"});');
                        $model_html = '<div id="update'.$data['id'].'" class="fade modal" role="dialog" tabindex="-1"><div class="modal-dialog "><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div><div class="modal-body"></div></div></div></div>';
                        $html = '<a data-toggle="modal" data-target="#update'.$data['id'].'" href="javascript:;" title="编辑">'.$icon.'</a>';
                        $result = $html.$model_html;
                    }
                    return $result;
                },
                'delete' => function ($url,$data,$key){
                    $icon = '<span class="glyphicon glyphicon-trash"></span>';
                    $html = '';
                    if($this->context->user->rid > 1 && $data['rid'] == 2)
                        $html = '<a style="color:#d1d1d1;" title="删除">'.$icon.'</a>';
                    else
                        $html = '<a href="delete?id='.$data['id'].'" title="删除" onclick="return confirm(\'是否删除该用户？\');">'.$icon.'</a>';
                    return $html;
                },
                'power' => function($url,$data,$key){
                    $icon = '<span class="glyphicon glyphicon-king"></span>';
                    $html = '';
                    if($this->context->user->rid > 1 && $data['rid'] == 2)
                        $html = '<a style="color:#d1d1d1;" title="授权">'.$icon.'</a>';
                    else
                        $html = '<a href="/user-permission/index?uid='.$data['id'].'" title="授权">'.$icon.'</a>';
                    return $html;
                }
            ],
        ]
    ],
]);
?>