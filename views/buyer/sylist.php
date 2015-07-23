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
use yii\web\View;
use yii\widgets\LinkPager;

$this->title = '淘宝号数据同步';
$this->params['breadcrumbs'][] = $this->title;
?>

    <input type="hidden" id="page" name="page" value="3"/>
    <div style="margin-bottom: 5px;">
        <button type="button" id="btnRefresh" class="btn-primary pull-right btn" style="margin-left:10px;">刷新</button>
        <button type="button" id="btnSyc" class="btn-primary pull-right btn" >开始同步</button>
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
        [
            'attribute'=>'帐号',
            'value'=>function($data){
                return $data['account'];
            },
            'contentOptions'=>function($data){
                return ['id'=>'account'.$data['id']];
            }
        ],
        [
            'attribute'=>'平台',
            'value'=>function($data){
                return $data['platform'];
            }
        ],
        [
            'attribute'=>'实名认证',
            'value'=>function($data){
                if(empty($data['id_verifi']))
                    $data['id_verifi'] = '';
                return $data['id_verifi'];
            },
            'contentOptions'=>function($data){
                return ['id'=>'id_verifi'.$data['id']];
            }
        ],
        [
            'attribute'=>'信用等级',
            'value'=>function($data){
                return $data['credit'];
            },
            'contentOptions'=>function($data){
                return ['id'=>'credit'.$data['id']];
            }
        ],
        [
            'attribute'=>'月',
            'value'=>function($data){
                return $data['month'];
            },
            'contentOptions'=>function($data){
                return ['id'=>'month'.$data['id']];
            }
        ],
        [
            'attribute'=>'周',
            'value'=>function($data){
                return $data['week'];
            },
            'contentOptions'=>function($data){
                return ['id'=>'week'.$data['id']];
            }
        ],
        [
            'attribute'=>'注册地区',
            'value'=>function($data){
                if(empty($data['regarea']))
                    $data['regarea'] = '';
                return $data['regarea'];
            },
            'contentOptions'=>function($data){
                return ['id'=>'regarea'.$data['id']];
            }
        ],
        [
            'attribute'=>'注册时间',
            'value'=>function($data){
                return $data['regtime'];
            },
            'contentOptions'=>function($data){
                return ['id'=>'regtime'.$data['id']];
            }
        ],
        [
            'attribute'=>'添加时间',
            'value'=>function($data){
                return $data['create_time'];
            }
        ],
        [
            'attribute'=>'状态',
            'value'=>function($data){
                return '未同步';
            },
            'contentOptions'=>function($data){
                return ['id'=>'syc'.$data['id']];
            }
        ]
    ],
    'rowOptions'=>function($model, $key, $index, $grid){
            return ['id'=>$model['id']];
    }

]);
?>
<?php Yii::$app->view->registerJs('
$("#btnSyc").click(function(){
            alert(1);
            $("#w0 tbody tr").each(function(){
                $("#id_verifi"+this.id).text("loading...");
                $("#credit"+this.id).text("loading...");
                $("#regarea"+this.id).text("loading...");
                $("#regtime"+this.id).text("loading...");
                $("#syc"+this.id).text("loading...");
                GetResult($("#account"+this.id).text(),this.id);
            });
        });

        $("#btnRefresh").click(function(){
            location.href = "/buyer/sylist";
        });
');?>
    <script> <!-- 编写script标签是为了编辑器识别js代码，可以省略 -->
        <?php $this->beginBlock('js_end') ?>
        var o = "22e79218965eb72c32a549dd5a331000";
        var S = "8648sjjdk28729mvb28947loek39dj2h";
        var p = "22e79218965eb72c32a549dd5a331100";
        function GetResult(skey,id) {
            var k = "83475893758937598748fgr";

            var q =S;
            var i = q;
            var va = CryptoJS.enc.Utf8.parse(i);

            var iv  = CryptoJS.enc.Utf8.parse('1234567812345678');
            var code = "taodaso";
            var tdskey = CryptoJS.AES.encrypt(skey+code, va, {iv:iv});

            $.ajax({
                type: "POST",
                url: '/buyer/syc',
                data: {data:"username=" + encodeURI(skey)+"&tdskey="+encodeURI(tdskey)},
                success: function(data, textStatus) {
                    $("#info").html(data);
                    getV(id);
                }
            });
        }

        function getV(id){
            //获取注册时间
            var regtime = $("#info").find(".datany_topZ ul li").eq(1).find("font").text();//.find("td font").text();
            //获取认证状态
            var id_verifi = $("#info").find(".datany_topZ ul li").eq(3).find("font").text();
            //获取注册地区
            $("#info").find(".datany_topZ ul li").eq(4).find("span").remove();
            var regarea = $("#info").find(".datany_topZ ul li").eq(4).text();
            //获取信用
            var credit = $("#info").find(".datany_topZ ul li").eq(0).find("font").text();
            //alert(tr1);
            //$("#info1").text(tr1);
            //最近一周
            var week = $("#info").find(".buyerBox ul li").eq(1).find("a").eq(0).find("font").text();
            var month = $("#info").find(".buyerBox ul li").eq(2).find("a").eq(0).find("font").text();

            $("#id_verifi"+id).text(id_verifi);
            $("#credit"+id).text(credit);
            $("#regarea"+id).text(regarea);
            $("#regtime"+id).text(regtime);
            $("#month"+id).text(month);
            $("#week"+id).text(week);
            $("#syc"+id).text('正在保存...');

            gStart("credit"+id);

            $.ajax({
                type: "POST",
                url: "/buyer/sycsave?id="+id,
                data:{credit:credit,regtime:regtime,id_verifi:id_verifi,regarea:regarea,month:month,week:week},
                success: function(data){
                    if(data == 1){
                        $("#syc"+id).html("<font color=\"green\"><b>同步完成</b></font>");
                    }else{
                        $("#syc"+id).html("<font color=\"red\"><b>同步失败</b></font>");
                    }
                }
            });
        }

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
                var fenHtml = start+'点 <img alt="'+start+'" src="'+imgDir+src+'"/>';
                $("#"+id).html(fenHtml);
            }
        }
        <?php $this->endBlock(); ?>
    </script>
<?php $this->registerJs($this->blocks['js_end'],View::POS_END);//将编写的js代码注册到页面底部 ?>
<?php $this->registerJsFile("/assets/js/vanfon_jm.js",['position' => View::POS_HEAD]); ?>
