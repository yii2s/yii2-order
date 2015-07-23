<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/14
 * Time: 9:56
 */
namespace app\models;
use PDO;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
/**
 * This is the model class for table "{{order}}".
 *
 * The followings are the available columns in table '{{order}}':
 * @property integer $id
 * @property string $buyer
 * @property string $shop
 * @property string $commodity
 * @property string $order_no
 * @property string $money
 * @property string $create_time
 * @property integer $uid
 * @property string $address
 * @property string $order_time
 * @property integer $statu
 * @property string $entrance
 * @property integer $fee
 */
class Order extends ActiveRecord {

    public static $Audits = array(
        '0' => '待审核',
        '1' => '通过',
        '2' =>  '未通过'
    );

    public static $_AUDIT_PEND = 0;
    public static $_AUDIT_ACCESS = 1;
    public static $_AUDIT_NOT_ACCESS = 2;

    public static function tableName(){
        return Yii::$app->params['tablePrefix'].'order';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['buyer', 'commodity'];
        return $scenarios;
    }

    public function rules()
    {
        return [
            [['create_time','uid','address','order_time','statu','fee'],'default'],
            ['buyer', 'required', 'message' => '请填写买家.'],
            //['shop', 'required', 'message' => '请填写店铺.'],
            ['commodity', 'required', 'message' => '请填写ID.'],
            ['order_no', 'required', 'message' => '请填写订单号.'],
            ['money', 'required', 'message' => '请填写金额.'],
            ['entrance','required','message' => '请填写浏览入口'],
            ['buyer', 'validateBuyer','on'=>'create'],
            ['commodity', 'validateCommodity'],
            ['order_no','validateOrder'],
            ['entrance','validateEntrance']
        ];
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'buyer' => '买家',
            'shop' => '商家',
            'commodity' => '商品',
            'order_no' => '订单号',
            'money' => '金额',
            'create_time' => '添加时间',
            'uid' => '提交用户',
            'address' => '收货地址',
            'order_time' => '下单时间',
            'statu' => '状态',
            'entrance' => '浏览入口',
            'fee' => '佣金'
        );
    }

    public function validateBuyer($attribute, $params){
        echo 'validateBuyer';
        $buyer = Buyer::find()
            ->where('account = :account',[':account' => $this->$attribute])
            ->one();
        if (is_null($buyer)) {
            return $this->addError($attribute, '买家不存在');
        }
        $buyer->isBuy = 1;
        $buyer->buy_time = $this->order_time;
        $buyer->save();

        $check = false;
        if($this->scenario == 'create'){
            $check = true;
        }
        if($this->scenario == 'update'){
            $order = Order::find($this->id);
            if(!is_null($order) && $order->buyer != $this->$attribute)
                $check = true;
        }
        if($check){
            $count = (new Query())
                ->from(Order::tableName())
                ->where('buyer = :buyer AND commodity = :commodity AND DATE(create_time) = DATE (now())',[':buyer'=>$this->$attribute,':commodity'=>$this->commodity])
                ->count();
            if($count > 0){
                return $this->addError($attribute, '该买家今天已提交该商品订单');
            }
        }
    }

    public function validateCommodity($attribute, $params){
        echo 'validateCommodity';
        $commodity = Commodity::find()
            ->where('commodity_id = :commodity_id',[':commodity_id' => $this->$attribute])
            ->one();
        if (is_null($commodity)) {
            return $this->addError($attribute, '商品不存在');
        }
        if($this->scenario == 'create'){
            $orderCount = (new Query())
                ->from(Order::tableName())
                ->where('commodity = :commodity',[':commodity'=>$this->$attribute])
                ->count();
            $coCount = (new Query())
                ->select(CommodityOrderDetail::tableName().'.num as num')
                ->from(CommodityOrder::tableName())
                ->leftJoin(CommodityOrderDetail::tableName(),CommodityOrderDetail::tableName().'.coid = '.CommodityOrder::tableName().'.id')
                ->where('commodity_id = :commodity_id',[':commodity_id'=>$this->$attribute])
                //->groupBy(CommodityOrder::tableName().'.id')
                ->sum('num');
            if(($orderCount+1) == $coCount){
                $sql="update ".CommodityOrder::tableName()." set op_statu = ".CommodityOrder::$_OP_FINISH." where commodity_id = :commodity_id";
                $db = Yii::$app->db;
                $command=$db->createCommand($sql);
                $command->bindParam(":commodity_id",$this->$attribute,PDO::PARAM_STR);
                $command->execute();
            }
            if($orderCount >= $coCount){
                return $this->addError($attribute, '该商品订单已满');
            }
        }
    }

    public function validateOrder($attribute, $params){
        echo 'validateOrder';
        $check = false;
        if($this->scenario == 'create'){
            $check = true;
        }
        if($this->scenario == 'update'){
            $order = Order::find($this->id);
            if(!is_null($order) && $order->order_no != $this->$attribute)
                $check = true;
        }
        if($check){
            $order = Order::find()
                ->where('order_no = :order_no',[':order_no' => $this->$attribute])
                ->one();
            if (!is_null($order)) {
                return $this->addError($attribute, '该订单已存在');
            }
        }
    }

    public function validateEntrance($attribute, $params){
        echo 'validateEntrance';
        $commodityOrderDetail = (new Query())
            ->select(CommodityOrderDetail::tableName().'.*')
            ->from(CommodityOrder::tableName())
            ->leftJoin(CommodityOrderDetail::tableName(),CommodityOrderDetail::tableName().'.coid = '.CommodityOrder::tableName().'.id')
            ->where('commodity_id = :commodity_id and entrance = :entrance',[':commodity_id'=>$this->commodity,':entrance'=>$this->$attribute])
            ->one();
        if(is_null($commodityOrderDetail))
            return $this->addError($attribute, '浏览入口不存在');
    }

} 