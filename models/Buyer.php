<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/2
 * Time: 15:56
 */
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "{{buyer}}".
 *
 * The followings are the available columns in table '{{buyer}}':
 * @property integer $id
 * @property string $platform
 * @property string $account
 * @property string $pass
 * @property string $pay_account
 * @property string $pay_pass
 * @property string $regphone
 * @property string $regtime
 * @property string $regarea
 * @property string $credit
 * @property integer $week
 * @property integer $month
 * @property string $buy_time
 * @property string $remark
 * @property string $create_time
 * @property string $id_verifi
 * @property integer $isSync
 * @property string $address
 * @property integer $isBuy
 * @property integer $weight
 * @property integer $credit_temp
 */
class Buyer extends ActiveRecord {

    public static  function tableName(){
        return Yii::$app->params['tablePrefix'].'buyer';
    }

    public $file;
    public static $weights = [
        ['id'=>1],
        ['id'=>2],
        ['id'=>3],
        ['id'=>4],
        ['id'=>5]
    ];

    public function rules()
    {
        return [
            [['regtime','regarea','credit','week','month','buy_time','remark','create_time','id_verifi','isSync','address'],'default'],
            ['platform', 'required', 'message' => '请选择平台！'],
            ['account', 'required', 'message' => '请填写帐号！'],
            ['pass', 'required', 'message' => '请填写密码！'],
            ['pay_account', 'required', 'message' => '请填写支付帐号！'],
            ['pay_pass', 'required', 'message' => '请填写支付密码！'],
            //['regarea', 'required', 'message' => '请填写注册地区！'],
            ['regphone', 'required', 'message' => '请填写注册手机！'],
            [['isBuy','weight','credit_temp'],'integer'],
            [['file'], 'file']
        ];
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'platform' => '平台',
            'account' => '帐号',
            'pass' => '密码',
            'pay_account' => '支付帐号',
            'pay_pass' => '支付密码',
            'regphone' => '注册手机',
            'regtime' => '注册时间',
            'regarea' => '注册地区',
            'credit' => '信用等级',
            'week' => '周',
            'month' => '月',
            'buy_time' => '购买时间',
            'remark' => '备注',
            'create_time' => '添加时间',
            'id_verifi' => '实名认证',
            'isSync' => '是否已同步',
            'address' => '收货地址',
            'isBuy' => '已经购买',
            'file' => '导入小号',
            'weight' => '权重'
        );
    }

} 