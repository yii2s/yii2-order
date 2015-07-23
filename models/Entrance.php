<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/4/30
 * Time: 19:16
 */
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "{{entrance}}".
 *
 * The followings are the available columns in table '{{entrance}}':
 * @property integer $id
 * @property string $name
 * @property string $create_time
 */
class Entrance extends ActiveRecord {

    public static function tableName(){
        return Yii::$app->params['tablePrefix'].'entrance';
    }

    public function rules()
    {
        return [
            ['name', 'required', 'message' => '请输入入口名称.'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => '入口名称',
            'create_time' => '添加时间',
        );
    }
} 