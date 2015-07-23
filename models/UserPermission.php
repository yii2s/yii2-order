<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/26
 * Time: 20:32
 */
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "{{user_permission}}".
 *
 * The followings are the available columns in table '{{user_permission}}':
 * @property integer $uid
 * @property integer $mid
 */
class UserPermission extends ActiveRecord{

    public static function tableName(){
        return Yii::$app->params['tablePrefix'].'user_permission';
    }
}