<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/6/2
 * Time: 23:50
 */
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "{{statistics}}".
 *
 * The followings are the available columns in table '{{statistics}}':
 * @property integer $id
 * @property integer $coid
 * @property string $shop
 * @property string $platform
 * @property string $commodity
 * @property string $corpus
 * @property string $real_income
 * @property string $total_income
 * @property string $total_fee
 * @property string $fact_fee
 * @property integer $total_num
 * @property integer $budget_num
 * @property string $handle_time
 * @property integer $uid
 */
class Statistics extends ActiveRecord{
    public static  function tableName(){
        return Yii::$app->params['tablePrefix'].'statistics';
    }

    public function rules()
    {
        return [
            [['coid','shop','platform','commodity','corpus','real_income','total_income','total_fee','fact_fee','total_num','budget_num','handle_time','uid'],'default'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'coid' => '下单ID',
            'shop' => '店铺',
            'platform' => '平台',
            'commodity' => '商品',
            'corpus' => '本金',
            'real_income' => '实收金额',
            'total_income' => '已收金额',
            'total_fee' => '总佣金',
            'fact_fee' => '实际佣金',
            'total_num' => '提交笔数',
            'budget_num' => '商家笔数',
            'handle_time' => '执行时间',
            'uid' => '用户ID'
        );
    }
}