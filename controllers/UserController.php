<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/16
 * Time: 22:54
 */
namespace app\controllers;
use app\models\form\UpdatePasswordForm;
use app\models\Role;
use app\models\User;
use app\base\BaseController;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;
use yii\web\MethodNotAllowedHttpException;

class UserController extends BaseController{

    public function actionIndex(){
        $dataProvider = new ActiveDataProvider([
            'query' => User::findBySql('select * from '.User::tableName().' where isadmin = 0'),
            'pagination' => [
                'pagesize' => '20',
         ]
        ]);
        return $this->render('index',array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionCreate(){
        $model = new User();
        if ($model->load(Yii::$app->request->post())) {
            $model->scenario = 'create';
            // 获取用户输入的数据，验证并保存
            $model->create_time = date( 'Y-m-d H:i:s');
            if($model->save()){
                $this->redirect('/user/index');
            }
        }

        $roles = Role::findBySql('SELECT * FROM '.Role::tableName().' where id > 1')->all();
        return $this->render('create',array(
            'model' => $model,
            'roles' => $roles
        ));
    }

    public function actionUpdate($id){
        $model = User::findOne($id);
        if(empty($model))
            throw new HttpException(404,'用户不存在！');
        if($this->user->rid > 1 && $model->rid == 2)
            throw new MethodNotAllowedHttpException('权限不够！');
        if ($model->load(Yii::$app->request->post())) {
            // 获取用户输入的数据，验证并保存
            if($model->save()){
                $this->redirect('/user/index');
            }
        }
        $roles = Role::findBySql('SELECT * FROM '.Role::tableName().' where id > 1')->all();
        return $this->renderAjax('update',array(
            'model' => $model,
            'roles' => $roles
        ));
    }

    public function actionDelete($id){
        $user = User::findOne($id);
        if(empty($user))
            throw new HttpException(404,'用户不存在！');
        if($this->user->rid > 1 && $user->rid == 2)
            throw new MethodNotAllowedHttpException('权限不够！');
        $user->delete();
        $this->redirect('/user/index');
    }

    public function actionUpdatepwd(){
        $model = new UpdatePasswordForm();
        if ($model->load(Yii::$app->request->post())) {
            // 获取用户输入的数据，验证并保存
            if ($model->validate()){
                $user = User::findOne($this->user->id);
                $user->password = $model->password;
                if($user->save()){
                    Yii::$app->user->logout();
                    echo "	<meta charset='utf-8'>";
                    echo "<script>alert('修改成功！')</script>";
                    echo "<script>location.href='/';</script>";
                    return;
                }
            }
        }
        return $this->render('updatepwd',array(
            'model' => $model,
        ));
    }
}