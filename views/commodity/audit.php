<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/27
 * Time: 21:30
 */
use app\models\Commodity;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\LinkPager;

$this->title = '商品审核';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::beginForm(['commodity/audit'],'post',['id'=>'search_form']) ?>
    <div>
        <div style="margin-bottom: 5px;">
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'shop',['class'=>'form-control','placeholder'=>'店铺'])?>
            </div>
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'commodity',['class'=>'form-control','placeholder'=>'ID'])?>
            </div>
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'sku',['class'=>'form-control','placeholder'=>'SKU'])?>
            </div>
            <div class="clearfix"></div>
        </div>
        <div style="margin-bottom: 5px;">
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'entrance',['class'=>'form-control','placeholder'=>'浏览入口'])?>
            </div>
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'btime',['class'=>'form-control','placeholder'=>'开始','onfocus'=> Html::decode('WdatePicker({dateFmt:&quot;yyyy-MM-dd HH:mm:ss&quot;,maxDate:&quot;#F{$dp.$D(\'commoditysearchform-etime\')}&quot;})')])?>
            </div>
            <div class="col-sm-2" style="padding-left:0px;">
                <?= Html::activeTextInput($model,'etime',['class'=>'form-control','placeholder'=>'结束','onfocus'=> Html::decode('WdatePicker({dateFmt:&quot;yyyy-MM-dd HH:mm:ss&quot;,minDate:&quot;#F{$dp.$D(\'commoditysearchform-btime\')}&quot;})')])?>
            </div>
            <div class="col-sm-1" style="padding-left:0px;">
                <?= Html::submitButton('查询',['class' => 'btn-primary pull-left btn'])?>
            </div>
            <div class="clearfix"></div>
        </div>
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
        [
            'attribute'=>'店铺',
            'value'=>function($data){
                return $data['shop'];
            }
        ],
        [
            'attribute'=>'平台',
            'value'=>function($data){
                return $data['platform'];
            }
        ],
        [
            'attribute'=>'ID',
            'value'=>function($data){
                return $data['commodity_id'];
            }
        ],
        [
            'attribute'=>'SKU',
            'value'=>function($data){
                return $data['sku'];
            }
        ],
        [
            'attribute'=>'要求',
            'value'=>function($data){
                if(empty($data['rule']))
                    $data['rule'] = '';
                return $data['rule'];
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => '买手信用',
            'content' => function ($data){
                if(empty($data['credit']))
                    $data['credit'] = '';
                return '<span id="credit'.$data['id'].'">'.$data['credit'].'</span>';
            }
        ],
        [
            'attribute'=>'添加时间',
            'value'=>function($data){
                return $data['create_time'];
            }
        ],
        [
            'class' => 'yii\grid\Column',
            'header'=>'审核状态',
            'content'=>function($data){
                return '<a href="#" data-type="select" data-name="statu" data-pk="'.$data['id'].'" data-value="'.$data['statu'].'" data-title="'.Commodity::$Audits[$data['statu']].'">'.Commodity::$Audits[$data['statu']].'</a>';
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn','header' => '操作',
            'template'=>'{view}',
            'buttons'=>[
                'view' => function($url,$data,$key){
                    $icon = '<span class="glyphicon glyphicon-eye-open"></span>';
                    $html = '<a href="audit-view?id='.$data['id'].'" title="查看">'.$icon.'</a>';
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
$("td span").each(function(){
        gStart(this.id);
    });
');?>
<?php $this->registerJsFile("/assets/My97DatePicker/WdatePicker.js",['position' => View::POS_HEAD]); ?>
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
        $('a[data-name="statu"]').editable({
            source: <?=$audit_json?>,
            url: '/commodity/update-audit-statu',
            success: function(response, newValue) {
                response = jQuery.parseJSON(response);
                if(response.status == 'error') return response.msg; //msg will be shown in editable form
            },
            validate: function(value) {
                if($.trim(value) == 0) {
                    return '请审核通过或者不通过';
                }
                var ret = window.confirm("确定提交?");
                if(!ret) {
                    return "用户取消操作";
                }
            }
        });
    });
</script>
<script type="text/javascript">
    //信用图
    var pfarry = [
        'b_red_',
        'b_blue_',
        'b_cap_',
        'b_crown_'
    ];
    function gStart(id){
        var start = $("#"+id).text();
        var ary = start.split('－');
        var fen = ary[0];

        var imgDir = '/assets/images/level/';
        var src = '';
        if(fen >= 4 && fen <= 10){
            src = pfarry[0]+'1.gif';
        }else if(fen >= 11 && fen <= 40){
            src = pfarry[0]+'2.gif';
        }else if(fen >= 41 && fen <= 90){
            src = pfarry[0]+'3.gif';
        }else if(fen >= 91 && fen <= 150){
            src = pfarry[0]+'4.gif';
        }else if(fen >= 151 && fen <= 250){
            src = pfarry[0]+'5.gif';
        }else if(fen >= 251 && fen <= 500){
            src = pfarry[1]+'1.gif';
        }else if(fen >= 501 && fen <= 1000){
            src = pfarry[1]+'2.gif';
        }else if(fen >= 1001 && fen <= 2000){
            src = pfarry[1]+'3.gif';
        }else if(fen >= 2001 && fen <= 5000){
            src = pfarry[1]+'4.gif';
        }else if(fen >= 5001 && fen <= 10000){
            src = pfarry[1]+'5.gif';
        }else if(fen >= 10001 && fen <= 20000){
            src = pfarry[2]+'1.gif';
        }else if(fen >= 20001 && fen <= 50000){
            src = pfarry[2]+'2.gif';
        }else if(fen >= 50001 && fen <= 100000){
            src = pfarry[2]+'3.gif';
        }else if(fen >= 100001 && fen <= 200000){
            src = pfarry[2]+'4.gif';
        }else if(fen >= 200001 && fen <= 500000){
            src = pfarry[2]+'5.gif';
        }else if(fen >= 500001 && fen <= 1000000){
            src = pfarry[3]+'1.gif';
        }else if(fen >= 1000001 && fen <= 2000000){
            src = pfarry[3]+'2.gif';
        }else if(fen >= 2000001 && fen <= 5000000){
            src = pfarry[3]+'3.gif';
        }else if(fen >= 5000001 && fen <= 10000000){
            src = pfarry[3]+'4.gif';
        }else if(fen >= 10000001){
            src = pfarry[3]+'5.gif';
        }else{
            src = '';
        }
        if(src != ''){
            //var fenHtml = start+'点 <img alt="'+start+'" src="'+imgDir+src+'"/>';
            var fenHtml = '<img alt="'+start+'" src="'+imgDir+src+'"/>';
            $("#"+id).html(fenHtml);
        }
    }
</script>