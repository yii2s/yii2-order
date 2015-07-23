<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/26
 * Time: 20:35
 */
namespace app\controllers;
use app\models\Menu;
use app\models\User;
use app\models\UserPermission;
use app\base\BaseController;
use yii\web\HttpException;
use yii\web\MethodNotAllowedHttpException;

class UserPermissionController extends BaseController{

    public function actionIndex($uid){
        $user = User::findOne($uid);
        if(empty($user))
            throw new HttpException(404,'用户不存在！');
        if($this->user->rid > 1 && $user->rid == 2)
            throw new MethodNotAllowedHttpException('权限不够！');
        $menus = Menu::find()->orderBy('sortNum desc')->all();
        $userPermissions = UserPermission::find()->where('uid = :uid',[':uid'=>$uid])->all();
        $permissions = array();
        foreach ($userPermissions as $userPermission){
            $permissions[$userPermission->mid] = $userPermission->mid;
        }
        return $this->render('index',array(
            'menus' => $menus,
            'permissions' => $permissions,
            'uid' => $uid
        ));
    }

    public function actionUpdate($uid){
        $user = User::findOne($uid);
        if(empty($user))
            throw new HttpException(404,'用户不存在！');
        if($this->user->rid > 1 && $user->rid == 2)
            throw new MethodNotAllowedHttpException('权限不够！');

        UserPermission::deleteAll(['uid'=>$uid]);
        if (isset($_POST['pmenus'])) {
            $pmenus = $_POST['pmenus'];
            foreach ($pmenus as $pmenu){
                $userPermission = new UserPermission();
                $userPermission->uid = $uid;
                $userPermission->mid = $pmenu;
                $userPermission->save();
            }
        }
        $this->redirect('/user/index');
    }

}