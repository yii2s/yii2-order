<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/6/3
 * Time: 16:20
 */
namespace app\models;
use Yii;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "{{log}}".
 *
 * The followings are the available columns in table '{{log}}':
 * @property integer $id
 * @property string $url
 * @property string $method
 * @property string $controller
 * @property string $action
 * @property int $uid
 * @property string $log_time
 */
class Log extends ActiveRecord{

    public static $Controllers = array(
        'buyer' => '买手',
        'commodity' => '商品',
        'commodity-order' =>  '放单',
        'commodity-order-detail' => '放单明细',
        'entrance' => '浏览入口',
        'log' => '日志',
        'order' => '订单',
        'platform' => '平台',
        'shop' => '店铺',
        'site' => '系统',
        'statistics' => '报表统计',
        'user' => '帐号',
        'user-permission' => '用户权限',
        'notice' => '公告'
    );

    public static $Actions = array(
        'index' => '默认页面',
        'update' => '修改',
        'create' => '创建',
        'delete' => '删除',
        'exportcsv' => '导出',
        'import' => '导入',
        'sylist' => '同步列表',
        'syc' => '同步代理',
        'sycsave' => '数据保存',
        'view' => '详情',
        'update-order-templet' => '更新订单模板',
        'delete-order-templet' => '删除订单模板',
        'audit' => '审核列表',
        'update-audit-statu' => '审核操作',
        'audit-view' => '审核详情',
        'update-order-detail' => '更改放单',
        'update-real-income' => '更改实际收入',
        'allot' => '分配',
        'saveallot' => '分配操作',
        'login' => '登录',
        'logout' => '退出',
        'updatepwd' => '修改密码'
    );

    public static function tableName(){
        return Yii::$app->params['tablePrefix'].'log';
    }

    public function rules()
    {
        return [
            [['url','method','controller','action','uid','log_time'],'default'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'url' => '请求地址',
            'method' => '请求方式',
            'controller' => '控制器',
            'action' => '函数',
            'uid' => '用户ID',
            'log_time' => '记录时间'
        );
    }
}