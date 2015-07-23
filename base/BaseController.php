<?php
namespace app\base;
use app\models\Log;
use app\models\Menu;
use app\models\UserPermission;
use Yii;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Request;

/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/26
 * Time: 23:21
 */
class BaseController extends Controller{

    public $menu = array();
    public $permissions = array();
    public $user;

    public $credits = [
        ['value'=>0,'text'=>'无'],
        ['value'=>4,'text'=>'1心'],
        ['value'=>11,'text'=>'2心'],
        ['value'=>41,'text'=>'3心'],
        ['value'=>91,'text'=>'4心'],
        ['value'=>151,'text'=>'5心'],
        ['value'=>251,'text'=>'钻'],
    ];

    public function getMenu(){
        return $this->menu;
    }

    /**
     * Initializes the controller
     */
    public function init(){
        $this->user = Yii::$app->session->get('user');
        if($this->user == null && !Yii::$app->user->isGuest){
            Yii::$app->user->logout();
            $this->redirect('/');
        }
        if(Yii::$app->user->isGuest){
            $order = ['label' => '提交订单', 'url' => ['/order/guest-create']];
            array_push($this->menu,$order);
            $login = ['label' => '登录', 'url' => ['/site/login']];
            array_push($this->menu,$login);
            return;
        }

        //init user permissions
        $userPermissions = UserPermission::find()->where('uid = :uid',[':uid'=>$this->user->id])->all();
        foreach ($userPermissions as $userPermission){
            $this->permissions[$userPermission->mid] = $userPermission->mid;
        }
        //init menu
        $index = ['label' => '首页','url' => ['/site/index']];
        array_push($this->menu,$index);
        $menus = Menu::find()->orderBy('sortNum desc')->all();
        if($menus){
            foreach ($menus as $menu){
                if(!$this->checkMenuPermission($menu->id))
                    continue;
                if($menu->pid != 0)
                    continue;

                $item_menus = array();
                foreach ($menus as $row) {
                    if(!$this->checkMenuPermission($row->id))
                        continue;
                    if ($menu->id == $row->pid) {
                        $item = array('label' => $row->name, 'url' => array($row->url));
                        if (empty($row->url)) {
                            $item = array('label' => $row->name);
                        }
                        array_push($item_menus, $item);
                    }
                }
                $parent_menu = array();
                if (count($item_menus) > 0) {
                    $parent_menu = array('label' => $menu->name, 'items' => $item_menus);
                }else{
                    $parent_menu = array('label'=>$menu->name,'url' => array($menu->url));
                    if(empty($menu->url)){
                        $parent_menu = array('label'=>$menu->name);
                    }
                }
                array_push($this->menu,$parent_menu);
            }
        }
        if(!Yii::$app->user->isGuest){
            $updatepwd = ['label' => '修改密码','url' => ['/user/updatepwd'],'linkOptions' => ['data-method' => 'post']];
            array_push($this->menu,$updatepwd);
            $logout = ['label' => '退出 (' . Yii::$app->user->identity->username . ')','url' => ['/site/logout'],'linkOptions' => ['data-method' => 'post']];
            array_push($this->menu,$logout);
        }
    }

    private function checkMenuPermission($mid){
        if($this->user->isadmin == 1)
            return true;
        if(!empty($this->permissions[$mid]))
            return true;
        return false;
    }

    public function beforeAction($action){
        parent::beforeAction($action);
        $act = $action->id;
        $controller = $action->controller->id;
        $user = Yii::$app->session->get('user');

        $log = new Log();
        $log->url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];
        $log->method = Yii::$app->request->method;
        $log->controller = $controller;
        $log->action = $act;
        if($user != null)
            $log->uid = $user->id;
        $log->log_time = date( 'Y-m-d H:i:s');
        $log->save();

        if($controller == 'site')
            return true;
        if(Yii::$app->user->isGuest && $act != 'guest-create'){
            $this->redirect('/site/login');
        }

        if($this->isPermiss($user,$controller,$act)){
            return true;
        }else{
            throw new MethodNotAllowedHttpException('未被授权！');
        }
    }

    private function isPermiss($user,$controller,$action){
        $menu = Menu::find()->where('controller = :controller',[':controller'=>$controller])->andWhere('action = :action',[':action'=>$action])->one();
        //echo '$controller:'.$controller.'/$action:'.$action;
        $permiss = FALSE;
        if($menu){
            if($user->isadmin == 1 || !empty($this->permissions[$menu->id]))
                $permiss = TRUE;
        }else
            $permiss = TRUE;
        return $permiss;
    }
}