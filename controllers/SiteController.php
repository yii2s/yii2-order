<?php
namespace app\controllers;
use app\models\CommodityOrder;
use app\models\EntryForm;
use app\models\form\LoginForm;
use app\base\BaseController;
use app\models\Notice;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\ContactForm;
class SiteController extends BaseController{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex(){
        $notice = null;
        $order_in = 0;
        $order_finish = 0;
        $order_pend = 0;
        if(!empty($this->user)){
            $notice = Notice::find()->where('rids like :rids',[':rids' => '%,'.$this->user->rid.',%'])->orderBy('create_time desc')->one();
            $order_in = CommodityOrder::find()->where('op_statu = :op_statu',[':op_statu'=>CommodityOrder::$_OP_IN])->count();
            $order_finish = CommodityOrder::find()->where('op_statu = :op_statu',[':op_statu'=>CommodityOrder::$_OP_FINISH])->count();
            $order_pend = CommodityOrder::find()->where('statu = :statu',[':statu'=>CommodityOrder::$_AUDIT_PEND])->count();
        }
        return $this->render('index',[
            'notice' => $notice,
            'order_in' => $order_in,
            'order_finish' => $order_finish,
            'order_pend' => $order_pend
        ]);
    }

    public function actionLogin(){

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSay($message = 'Hello'){
        return $this->render('say',['message' => $message]);
    }
}
