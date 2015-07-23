<?php
/* @var $this yii\web\View */
$this->title = 'DD';
?>
<div class="site-index">

    <div class="body-content">
        <?php
        if(!Yii::$app->user->isGuest){
            ?>
            <div class="row">
                <div class="col-lg-4">
                    <h2>系统信息</h2>
                    <div class="w_bg">
                        <table class="table table-hover">
                            <tr>
                                <td>进行中订单：<?=$order_in?></td>
                            </tr>
                            <tr>
                                <td>已完成订单：<?=$order_finish?></td>
                            </tr>
                            <tr>
                                <td>待审核订单：<?=$order_pend?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-lg-8">
                    <?php
                    if(!empty($notice)){
                        ?>
                        <h2>公告</h2>
                        <h3><?= $notice->title;?></h3>
                        <p><?= $notice->content;?></p>
                        <p><?= $notice->create_time;?></p>
                    <?php
                    }
                    ?>
                </div>
            </div>
        <?php
        }
        ?>

    </div>
</div>
