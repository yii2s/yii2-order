<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/7
 * Time: 17:36
 */
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "{{commodity}}".
 *
 * The followings are the available columns in table '{{commodity}}':
 * @property integer $id
 * @property string $shop
 * @property integer $shop_id
 * @property string $commodity_id
 * @property string $sku
 * @property string $img
 * @property string $rule
 * @property string $buyer_rule
 * @property string $remark
 * @property string $create_time
 * @property integer $uid
 * @property integer $statu
 * @property integer $platform_id
 * @property string $platform
 * @property integer $credit
 * @property integer $trade_num
 */
class Commodity extends ActiveRecord {

    public static $Audits = array(
        '0' => '待审核',
        '1' => '通过',
        '2' =>  '未通过'
    );

    public static $_AUDIT_PEND = 0;
    public static $_AUDIT_ACCESS = 1;
    public static $_AUDIT_NOT_ACCESS = 2;

    public static  function tableName(){
        return Yii::$app->params['tablePrefix'].'commodity';
    }

    public function rules()
    {
        return [
            //['shop', 'required', 'message' => '请填写店铺.'],
            [['shop','shop_id','rule','buyer_rule','remark','create_time','uid','statu','platform_id','platform'],'default'],
            [['shop','shop_id'], 'required', 'message' => '请选择店铺.'],
            ['commodity_id', 'required', 'message' => '请填写ID.'],
            ['sku', 'required', 'message' => '请填写SKU信息.'],
            ['credit', 'required', 'message' => '请填写买手信用.'],
            ['trade_num', 'required', 'message' => '请填写买手交易数.'],
            ['img', 'required', 'message' => '请上传商品图.','on'=>'create'],
            [['trade_num'],'integer'],
            ['img','file','extensions' => 'gif,jpg,png,jpeg','wrongExtension' => '请上传图片格式为gif,jpg,png,jpeg的图片.']
        ];
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'shop' => '店铺',
            'shop_id' => '店铺ID',
            'commodity_id' => 'ID',
            'sku' => 'SKU',
            'img' => '商品图',
            'rule' => '要求',
            'buyer_rule' => '买手帐号要求',
            'remark' => '备注',
            'create_time' => '添加时间',
            'uid' => '用户ID',
            'statu' => '状态',
            'platform_id' => '平台ID',
            'platform' => '平台',
            'credit' => '买手信用',
            'trade_num' => '交易数'
        );
    }

} 