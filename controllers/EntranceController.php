<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/4/30
 * Time: 19:22
 */
namespace app\controllers;
use app\models\Entrance;
use app\base\BaseController;
use Yii;
use yii\data\ActiveDataProvider;
class EntranceController extends BaseController {

    public function actionIndex(){
        $dataProvider = new ActiveDataProvider([
            'query' => Entrance::find()
        ]);
        return $this->render('index',array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionCreate(){
        $model = new Entrance();
        if ($model->load(Yii::$app->request->post())) {
            // 获取用户输入的数据，验证并保存
            $model->create_time = date( 'Y-m-d H:i:s');
            if($model->save()){
                $this->redirect('/entrance/index');
            }
        }
        return $this->renderAjax('create',array(
            'model' => $model
        ));
    }

    public function actionUpdate($id){
        $model = Entrance::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            // 获取用户输入的数据，验证并保存
            if($model->save()){
                $this->redirect('/entrance/index');
            }
        }
        return $this->renderAjax('update',array(
            'model' => $model
        ));
    }

    public function actionDelete($id){
        Entrance::findOne($id)->delete();
        $this->redirect('/entrance/index');
    }

} 