<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/8
 * Time: 15:35
 */
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "{{commodity_order_templet}}".
 *
 * The followings are the available columns in table '{{commodity_order_templet}}':
 * @property integer $id
 * @property integer $cid
 * @property string $keyword
 * @property string $entrance
 * @property integer $eid
 * @property string $condition
 * @property integer $num
 * @property string $price
 * @property string $fee
 */
class CommodityOrderTemplet extends ActiveRecord {

    public static  function tableName(){
        return Yii::$app->params['tablePrefix'].'commodity_order_templet';
    }

    public function rules()
    {
        return [
            [['cid','entrance','eid','condition','fee'],'default'],
            ['keyword', 'required', 'message' => '请填写关键词.'],
            ['num', 'required', 'message' => '请填写笔数.'],
            ['price', 'required', 'message' => '请填写单价.'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'cid' => '商品ID',
            'keyword' => '关键词',
            'entrance' => '浏览入口',
            'eid' => '浏览入口ID',
            'condition' => '卡位条件',
            'num' => '笔数',
            'price' => '单价',
            'fee' => '佣金'
        );
    }
} 