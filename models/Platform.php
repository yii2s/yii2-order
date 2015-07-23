<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/4/27
 * Time: 16:35
 */
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "{{platform}}".
 *
 * The followings are the available columns in table '{{platform}}':
 * @property integer $id
 * @property string $name
 * @property string $create_time
 */
class Platform extends ActiveRecord {

    public static  function tableName(){
        return Yii::$app->params['tablePrefix'].'platform';
    }

    public function rules()
    {
        return [
            ['name', 'required', 'message' => '请输入平台名称.'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => '平台名称',
            'create_time' => '添加时间',
        );
    }

} 