<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/6/1
 * Time: 21:07
 */
use app\models\User;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;

$this->title = '分配放单';
$this->params['breadcrumbs'][] = ['label' => '放单管理', 'url' => ['/commodity-order/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="modal-header">
        <h4 class="modal-title">分配放单</h4>
    </div>
    <style>
        .help-block{margin-bottom: 5px;}
        .gv-table{text-align:center;}
        .gv-table th{text-align:center;}
    </style>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'summary' =>false,
    'emptyText' => '无关键词',
    'tableOptions' => ['class'=>'table table-striped table-bordered gv-table'],
    'columns' => [
        [
            'class' => 'yii\grid\Column',
            'header' => '关键词',
            'content'=>function($data){
                return $data['keyword'];
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '浏览入口',
            'content'=>function($data){
                return $data['entrance'];
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '卡位条件',
            'content'=>function($data){
                return $data['condition'];
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '笔数',
            'content'=>function($data){
                return $data['num'];
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '单价',
            'content'=>function($data){
                return $data['price'];
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '佣金',
            'content'=>function($data){
                return $data['fee'];
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '业务员',
            'content'=>function($data){
                $uid = $data['uid'];
                $user = User::findOne($uid);
                $uname = "未分配";
                if(!empty($user))
                    $uname = $user->username;
                $html = '<a href="#" data-type="select" data-name="uid" data-pk="'.$data['id'].'" data-value="'.$data['uid'].'" data-title="业务员">'.$uname.'</a>';
                return $html;
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '系统推荐',
            'content'=>function($data){
                $recommends = $data['recommends'];
                $is_recom = '否';
                $is_value = 0;
                if(!empty($recommends)){
                    $is_recom = '是';
                    $is_value = 1;
                }

                $html = '<a href="#" data-type="select" data-name="recommends" data-pk="'.$data['id'].'" data-value="'.$is_value.'" data-title="系统推荐">'.$is_recom.'</a>';
                return $html;
            }
        ],
    ],
]);?>

<!-- select2 -->
<link href="/assets/select2/css/select2.css" rel="stylesheet">
<script src="/assets/select2/js/select2.js"></script>
<!-- x-editable (bootstrap 3) -->
<link href="/assets/x-editable/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
<script src="/assets/x-editable/bootstrap3-editable/js/bootstrap-editable.js"></script>
<!-- select2 bootstrap -->
<link href="/assets/select2/css/select2-bootstrap.css" rel="stylesheet">
<!-- typeaheadjs -->
<link href="/assets/x-editable/inputs-ext/typeaheadjs/lib/typeahead.js-bootstrap.css" rel="stylesheet">
<script src="/assets/x-editable/inputs-ext/typeaheadjs/lib/typeahead.js"></script>
<script src="/assets/x-editable/inputs-ext/typeaheadjs/typeaheadjs.js"></script>
<script type="text/javascript">
    $(function(){
        $('a[data-name="uid"]').editable({
            source: <?=$users_json?>,
            url: '/commodity-order/update-order-detail',
            success: function(response, newValue) {
                response = jQuery.parseJSON(response);
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
                aleret('操作成功！');
            }
        });

        $('a[data-name="recommends"]').editable({
            source: <?=$recommend_json?>,
            url: '/commodity-order/update-order-detail',
            success: function(response, newValue) {
                response = jQuery.parseJSON(response);
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
                alert('操作成功！');
            }
        });
    });
</script>