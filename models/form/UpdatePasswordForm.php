<?php
namespace app\models\form;
use app\models\User;
use Yii;
use yii\base\Model;

/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/6/3
 * Time: 17:18
 */
class UpdatePasswordForm extends Model{

    public $oldpassword;
    public $password;
    public $repassword;

    public function rules(){
        return [
            ['oldpassword', 'required', 'message' => '请填写旧密码.'],
            ['password', 'required', 'message' => '请填写新密码.'],
            // rememberMe must be a boolean value
            ['repassword', 'required', 'message' => '请填写重复密码.'],
            // password is validated by validatePassword()
            ['oldpassword', 'validateOldpassword'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels(){
        return array(
            'oldpassword' => '旧密码',
            'password' => '新密码',
            'repassword' => '重复密码'
        );
    }

    public function validateOldpassword($attribute, $params){
        $user = Yii::$app->session->get('user');
        $user = User::findOne($user->id);
        if($user->password != $this->oldpassword)
            return $this->addError($attribute, '旧密码不正确');
    }

    public function validatePassword($attribute, $params){
        if($this->$attribute != $this->repassword)
            return $this->addError($attribute, '密码与重复密码不正确');
    }

}