<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/7
 * Time: 11:52
 */
namespace app\controllers;
use app\models\Shop;
use app\models\User;
use app\base\BaseController;
use Yii;
use yii\data\ActiveDataProvider;
class ShopController extends BaseController {

    public function actionIndex(){
        $dataProvider = new ActiveDataProvider([
            'query' => Shop::find()->orderBy('create_time desc')
        ]);
        return $this->render('index',array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionCreate(){
        $model = new Shop();
        if ($model->load(Yii::$app->request->post())) {
            // 获取用户输入的数据，验证并保存
            $model->create_time = date( 'Y-m-d H:i:s');
            if($model->save()){
                $this->redirect('/shop/index');
            }
        }
        return $this->renderAjax('create',array(
            'model' => $model
        ));
    }

    public function actionUpdate($id){
        $model = Shop::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            // 获取用户输入的数据，验证并保存
            if($model->save()){
                $this->redirect('/shop/index');
            }
        }
        return $this->renderAjax('update',array(
            'model' => $model
        ));
    }

    public function actionDelete($id){
        Shop::findOne($id)->delete();
        $this->redirect('/shop/index');
    }

    public function actionAllot($uid){
        $user = User::findOne($uid);
        if(empty($user) || $user->rid != 3)
            return;
        $shops = Shop::findBySql('SELECT * FROM '.Shop::tableName().' where uid = '.$user->id.' or uid = 0')->all();
        return $this->render('allot',array(
            'shops' => $shops,
            'uid' => $user->id
        ));
    }

    public function actionSaveallot(){
        $uid = Yii::$app->request->post('uid');
        $shops = Yii::$app->request->post('shop');
        Shop::updateAll(['uid'=>0],['uid'=>$uid]);
        foreach($shops as $shop){
            Shop::updateAll(['uid'=>$uid],['id'=>$shop]);
        }
        echo '<script>alert("保存成功！");location.href="/user/index";</script>';
    }

} 