<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/25
 * Time: 11:34
 */
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "{{role}}".
 *
 * The followings are the available columns in table '{{role}}':
 * @property integer $id
 * @property string $name
 */
class Role extends ActiveRecord {

    public static  function tableName(){
        return Yii::$app->params['tablePrefix'].'role';
    }

}