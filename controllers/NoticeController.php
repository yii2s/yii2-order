<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/6/4
 * Time: 21:07
 */
namespace app\controllers;
use app\base\BaseController;
use app\models\Notice;
use app\models\Role;
use Yii;
use yii\data\ActiveDataProvider;
class NoticeController extends BaseController{

    public function actionIndex(){
        $dataProvider = new ActiveDataProvider([
            'query' => Notice::find()->orderBy('create_time desc')
        ]);
        return $this->render('index',array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionCreate(){
        $model = new Notice();
        if ($model->load(Yii::$app->request->post())) {
            // 获取用户输入的数据，验证并保存
            $rids = $model->rids;
            $model->rids = ','.implode(',',$model->rids).',';
            $model->uid = $this->user->id;
            $model->create_time = date( 'Y-m-d H:i:s');
            if($model->save()){
                $this->redirect('/notice/index');
            }
        }
        $roles = Role::findBySql('SELECT * FROM '.Role::tableName().' where id > 1')->all();
        return $this->render('create',array(
            'model' => $model,
            'roles' => $roles
        ));
    }

    public function actionUpdate($id){
        $model = Notice::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            // 获取用户输入的数据，验证并保存
            $rids = $model->rids;
            $model->rids = ','.implode(',',$model->rids).',';
            if($model->save()){
                $this->redirect('/notice/index');
            }
        }
        $rids = $model->rids;
        $model->rids = explode(',',$rids);
        $roles = Role::findBySql('SELECT * FROM '.Role::tableName().' where id > 1')->all();
        return $this->render('update',array(
            'model' => $model,
            'roles' => $roles
        ));
    }

    public function actionDelete($id){
        Notice::findOne($id)->delete();
        $this->redirect('/notice/index');
    }
}