<?php
namespace app\models\form;
use yii\base\Model;

/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/6/2
 * Time: 19:11
 */
class StatisticsSearchForm extends Model{

    public $shop;
    public $commodity;
    public $platform;
    public $btime;
    public $etime;

    public function rules(){
        return [
            [['shop','commodity','platform','btime','etime'],'default']
        ];
    }

}