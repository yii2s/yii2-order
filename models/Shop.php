<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/7
 * Time: 11:46
 */
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "{{shop}}".
 *
 * The followings are the available columns in table '{{shop}}':
 * @property integer $id
 * @property string $shop_name
 * @property integer $uid
 * @property string $create_time
 */
class Shop extends ActiveRecord {

    public static  function tableName(){
        return Yii::$app->params['tablePrefix'].'shop';
    }

    public function rules()
    {
        return [
            ['shop_name', 'required', 'message' => '请填写店铺.'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'shop_name' => '店铺',
            'uid' => '关联帐号',
            'create_time' => '添加时间',
        );
    }

} 