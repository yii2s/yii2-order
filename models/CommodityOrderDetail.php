<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/11
 * Time: 17:43
 */
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "{{commodity_order_detail}}".
 *
 * The followings are the available columns in table '{{commodity_order_detail}}':
 * @property integer $id
 * @property integer $coid
 * @property string $keyword
 * @property string $entrance
 * @property integer $eid
 * @property string $condition
 * @property integer $num
 * @property string $price
 * @property string $fee
 * @property integer $uid
 * @property string $recommends
 * @property string $recommend_time
 */
class CommodityOrderDetail extends ActiveRecord {

    public static  function tableName(){
        return Yii::$app->params['tablePrefix'].'commodity_order_detail';
    }

    public function rules()
    {
        return [
            [['coid','entrance','eid','condition','fee','uid','recommends','recommend_time'],'default'],
            ['keyword', 'required', 'message' => '请填写关键词.'],
            ['num', 'required', 'message' => '请填写笔数.'],
            ['price', 'required', 'message' => '请填写单价.'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'coid' => '放单',
            'keyword' => '关键词',
            'entrance' => '浏览入口',
            'eid' => '浏览入口ID',
            'condition' => '卡位条件',
            'num' => '笔数',
            'price' => '单价',
            'fee' => '佣金',
            'uid' => '业务员',
            'recommends' => '推荐',
            'recommend_time' => '分配时间'
        );
    }

} 