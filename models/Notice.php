<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/6/4
 * Time: 20:56
 */
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "{{notice}}".
 *
 * The followings are the available columns in table '{{notice}}':
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $rids
 * @property integer $uid
 * @property string $create_time
 */
class Notice extends ActiveRecord{

    public static function tableName(){
        return Yii::$app->params['tablePrefix'].'notice';
    }

    public function rules()
    {
        return [
            [['uid','create_time'],'default'],
            [['title'], 'required', 'message' => '请填写标题.'],
            ['content', 'required', 'message' => '请填写内容.'],
            ['rids', 'required', 'message' => '请选择角色.']
        ];
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => '标题',
            'content' => '内容',
            'rids' => '角色',
            'action' => '函数',
            'uid' => '添加帐号',
            'create_time' => '添加时间'
        );
    }
}