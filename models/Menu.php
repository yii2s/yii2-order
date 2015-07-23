<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/25
 * Time: 21:33
 */
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "{{menu}}".
 *
 * The followings are the available columns in table '{{menu}}':
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property string $controller
 * @property string $action
 * @property integer $sortNum
 * @property integer $pid
 * @property string $createTime
 */
class Menu extends ActiveRecord{

    public static function tableName(){
        return Yii::$app->params['tablePrefix'].'menu';
    }
}