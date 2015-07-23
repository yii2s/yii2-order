<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/7
 * Time: 18:59
 */

namespace app\models\form;


use yii\base\Model;

class CommoditySearchForm extends Model {

    public $shop;
    public $commodity;
    public $sku;
    public $entrance;
    public $btime;
    public $etime;
    public $op_statu;
    public $statu;

    public function rules(){
        return [
            [['shop','commodity','sku','entrance','btime','etime','op_statu','statu'],'default']
        ];
    }

} 