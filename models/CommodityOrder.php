<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/11
 * Time: 17:37
 */
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "{{commodity_order}}".
 *
 * The followings are the available columns in table '{{commodity_order}}':
 * @property integer $id
 * @property integer $cid
 * @property string $shop
 * @property string $commodity_id
 * @property string $sku
 * @property string $img
 * @property string $rule
 * @property string $buyer_rule
 * @property string $remark
 * @property string $create_time
 * @property integer $uid
 * @property integer $statu
 * @property integer $op_statu
 * @property integer $platform_id
 * @property string $platform
 * @property string $real_income
 * @property string $handle_time
 * @property integer $credit
 * @property integer $trade_num
 */
class CommodityOrder extends ActiveRecord {

    public static $Audits = array(
        '0' => '待审核',
        '1' => '通过',
        '2' =>  '未通过'
    );

    public static $_AUDIT_PEND = 0;
    public static $_AUDIT_ACCESS = 1;
    public static $_AUDIT_NOT_ACCESS = 2;

    public static $Ops = array(
        '0' => '待执行',
        '1' => '进行中',
        '2' =>  '已完成'
    );

    public static $_OP_NOT = 0;
    public static $_OP_IN = 1;
    public static $_OP_FINISH = 2;

    public static  function tableName(){
        return Yii::$app->params['tablePrefix'].'commodity_order';
    }

    public function rules()
    {
        return [
            [['shop','cid','rule','buyer_rule','remark','create_time','uid','img','commodity_id','sku','statu','op_statu','platform_id','platform','real_income'],'default'],
            ['handle_time', 'required', 'message' => '请填写执行时间.'],
            ['credit', 'required', 'message' => '请填写买手信用.'],
            ['trade_num', 'required', 'message' => '请填写买手交易数.'],
            [['trade_num'],'integer'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'cid' => '商品',
            'shop' => '店铺',
            'commodity_id' => 'ID',
            'sku' => 'SKU',
            'img' => '商品图',
            'rule' => '要求',
            'buyer_rule' => '买手帐号要求',
            'remark' => '备注',
            'create_time' => '添加时间',
            'uid' => '用户ID',
            'statu' => '审核状态',
            'op_statu' => '当前状态',
            'platform_id' => '平台ID',
            'platform' => '平台',
            'real_income' => '实际收入',
            'handle_time' => '执行时间',
            'credit' => '买手信用',
            'trade_num' => '交易数'
        );
    }

} 