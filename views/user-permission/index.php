<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/26
 * Time: 12:04
 */
use yii\helpers\Html;

?>
<div class="modal-header">
    <h4 class="modal-title">用户授权</h4>
</div>
<?= Html::beginForm(['/user-permission/update?uid='.$uid],'post',['id'=>'form1']) ?>
<div class="modal-body">
<div class="form">
    <fieldset>
        <?php
        foreach ($menus as $menu){
            if($menu->pid == 0){
                $checked = empty($permissions[$menu->id])?'':'checked';
                echo '<div>';
                echo '<label style="letter-spacing:0px;font-size:12px;vertical-align:middle;" for="m-'.$menu->id.'">';
                echo '<input style="margin:0px;margin-right:5px;vertical-align:middle;" type="checkbox" name="pmenus[]" id="m-'.$menu->id.'" value="'.$menu->id.'" '.$checked.'/>'.$menu->name.'</label>';
                echo '</div>';
                foreach ($menus as $item){
                    if($menu->id == $item->pid){
                        $checked = empty($permissions[$item->id])?'':'checked';
                        echo '<div class="pure-u-1-3" style="margin-bottom:0px;">';
                        echo '<label style="font-size:12px;vertical-align:middle;font-weight: normal;" for="m-'.$item->id.'">';
                        echo '<input style="margin:0px;margin-right:5px;vertical-align:middle;" type="checkbox" name="pmenus[]" id="m-'.$item->id.'" value="'.$item->id.'" '.$checked.'/>'.$item->name.'</label>';
                        echo '</div>';
                    }
                }
                echo '<div style="clear:both;height:15px;"></div>';
            }

        }
        ?>
    </fieldset>
</div>
</div>
<div class="modal-footer">
    <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
    <button id="backButton" type="button" class="btn btn-default" onclick="history.go(-1);">返回</button>
    <div class="hidden">
        <script>
            $(function () {
                $('#backButton').on('click', function () {
                    window.location = '/user/index';
                });
            });
        </script>
    </div>
</div>
<?= Html::endForm() ?>