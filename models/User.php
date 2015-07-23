<?php
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property integer $rid
 * @property string $create_time
 * @property integer $isadmin
 * @property string $remark
 * @property string $authKey
 * @property string $accessToken
 */
class User extends ActiveRecord implements IdentityInterface{

//    public $id;
//    public $username;
//    public $password;
//    public $authKey;
//    public $accessToken;

    public static  function tableName(){
        return Yii::$app->params['tablePrefix'].'user';
    }

    public function rules()
    {
        return [
            [['authKey','accessToken','remark','create_time','rid'],'default'],
            ['username', 'required', 'message' => '请填写用户名.'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => '用户名已经被占用','on'=>'create'],
            ['password', 'required', 'message' => '请填写密码.']
        ];
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'username' => '用户名',
            'password' => '密码',
            'rid' => '角色',
            'create_time' => '添加时间',
            'isadmin' => '超级管理员',
            'remark' => '备注',
            'authKey' => 'authKey',
            'accessToken' => 'accessToken'
        );
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->authKey = Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }
}
