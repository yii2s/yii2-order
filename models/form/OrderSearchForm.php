<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/14
 * Time: 10:36
 */

namespace app\models\form;


use yii\base\Model;

class OrderSearchForm extends Model {

    public $buyer;
    public $shop;
    public $commodity;
    public $order_no;
    public $btime;
    public $etime;

    public function rules(){
        return [
            [['buyer','shop','commodity','order_no','btime','etime'],'default']
        ];
    }
} 