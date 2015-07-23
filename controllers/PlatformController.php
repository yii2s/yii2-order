<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/4/27
 * Time: 17:01
 */
namespace app\controllers;
use app\base\BaseController;
use Yii;
use yii\data\ActiveDataProvider;
use app\models\Platform;
class PlatformController extends BaseController {

    public function actionIndex(){
        $dataProvider = new ActiveDataProvider([
            'query' => Platform::find()
        ]);
        return $this->render('index',array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionCreate(){
        $model = new Platform();
        if ($model->load(Yii::$app->request->post())) {
            // 获取用户输入的数据，验证并保存
            $model->create_time = date( 'Y-m-d H:i:s');
            if($model->save()){
                $this->redirect('/platform/index');
            }
        }
        return $this->renderAjax('create',array(
            'model' => $model
        ));
    }

    public function actionUpdate($id){
        $model = Platform::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            // 获取用户输入的数据，验证并保存
            if($model->save()){
                $this->redirect('/platform/index');
            }
        }
        return $this->renderAjax('update',array(
            'model' => $model
        ));
    }

    public function actionDelete($id){
        Platform::findOne($id)->delete();
        $this->redirect('/platform/index');
    }

} 