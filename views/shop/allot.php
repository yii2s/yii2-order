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
    <h4 class="modal-title">分配店铺</h4>
</div>
<?= Html::beginForm(['/shop/saveallot'],'post',['id'=>'form1']) ?>
<div class="modal-body">
<div style="margin: 20px;">
    <div style="color: red;"><?php echo !empty($msg)?$msg:'';?></div>
    <input type="hidden" id="uid" name="uid" value="<?php echo $uid?>"/>
    <b>店铺：</b>
    <?php if(isset($shops)):foreach($shops as $shop) {?>
        <label class="checkbox-inline" for="<?php echo $shop['id']?>"><input type="checkbox" id="<?php echo $shop['id']?>" name="shop[]" <?php echo $uid == $shop['uid']?'checked':'' ?> value="<?php echo $shop['id']?>"><?php echo $shop['shop_name']?></label>
    <?php }endif;?>

</div>
</div>
<div class="modal-footer">
    <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
    <button type="button" class="btn btn-default" onclick="history.go(-1);">返回</button>
</div>
<?= Html::endForm() ?>