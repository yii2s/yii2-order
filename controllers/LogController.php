<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/6/3
 * Time: 16:43
 */
namespace app\controllers;
use app\base\BaseController;
use app\models\Log;
use yii\data\ActiveDataProvider;
class LogController extends BaseController{

    public function actionIndex(){

        $dataProvider = new ActiveDataProvider([
            'query' => Log::find()->orderBy('log_time desc')
        ]);
        return $this->render('index',array(
            'dataProvider' => $dataProvider,
        ));
    }
}