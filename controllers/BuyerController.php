<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/2
 * Time: 16:45
 */
namespace app\controllers;
use app\models\Buyer;
use app\models\Platform;
use app\base\BaseController;
use Http;
use Spreadsheet_Excel_Reader;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Query;
use yii\web\UploadedFile;
class BuyerController extends BaseController {

    public function actionIndex(){

        $model = new Buyer();
        $model->load(Yii::$app->request->post());

        //$data = Comment::find()->andWhere(['id' => '10']);
        //$pages = new Pagination(['totalCount' =>$data->count(), 'pageSize' => '2']);
        //$model = $data->offset($pages->offset)->limit($pages->limit)->all();
        $query = (new Query())->select('*')->from(Buyer::tableName())->where('1=1');
        //$sql = "select * from ".Buyer::tableName()." where 1=1";
        if(!empty($model->platform))
            //$sql .= " and platform = $model->platform";
            $query->andWhere('platform=:platform', [':platform' => $model->platform]);
        if(!empty($model->account))
            //$sql .= " and account = $model->account";
            $query->andWhere('account=:account', [':account' => $model->account]);
        if($model->isBuy == 1)
            //$sql .= " and isBuy = $model->isBuy";
            $query->andWhere('isBuy=:isBuy', [':isBuy' => $model->isBuy]);
        //echo $sql;
        //echo $query->count();
        $pages = new Pagination(['totalCount' =>$query->count(), 'pageSize' => '20']);
        //echo 'offset:'.$pages->offset.',limit:'.$pages->limit;
        $query->limit($pages->limit)->offset($pages->offset);
        $query->orderBy('rand()');
        //echo 'isBuy:'.$model->isBuy;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,//Buyer::findBySql($sql),
            'pagination' => false,
        ]);

        $platforms = Platform::find()->all();
        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'platforms' => $platforms,
            'pages' => $pages
        ]);
    }

    public function actionExportcsv(){
        $model = new Buyer();
        $model->load(Yii::$app->request->post());
        $query = (new Query())->select('*')->from(Buyer::tableName())->where('1=1');

        if(!empty($model->platform))
            $query->andWhere('platform=:platform', [':platform' => $model->platform]);
        if(!empty($model->account))
            $query->andWhere('account=:account', [':account' => $model->account]);
        if($model->isBuy == 1)
            $query->andWhere('isBuy=:isBuy', [':isBuy' => $model->isBuy]);
        $pages = new Pagination(['totalCount' =>$query->count(), 'pageSize' => '20']);
        $query->limit($pages->limit)->offset($pages->offset);
        $query->orderBy('create_time desc');

        $buyers = $query->all();
        $info = array();
        foreach($buyers as $buyer){
            $row = array();
            $row[] = $buyer['platform'];
            $row[] = $buyer['account'];
            $row[] = $buyer['pass'];
            $row[] = $buyer['pay_account'];
            $row[] = $buyer['pay_pass'];
            array_push($info, $row);
        }
        $detail = '';
        $subject =mb_convert_encoding("平台,帐号,密码,支付宝帐号,支付宝密码\n", "CP936", "UTF-8");
        foreach($info as $v) {
            foreach($v as $value) {
                $value = preg_replace('/\s+/', ' ', $value);
                $detail .= strlen($value) > 11 && is_numeric($value) ? '['.$value.'],' : $value.',';
            }
            $detail = $detail."\n";
        }

        $filename = time() . '_小号管理.csv';

        ob_end_clean();
        header('Content-Encoding: none');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$filename);
        header('Pragma: no-cache');
        header('Expires: 0');
        echo $subject;
        $detail = iconv("utf-8","gb2312",$detail);
        echo $detail;
    }

    public function actionCreate(){
        $model = new Buyer();
        if ($model->load(Yii::$app->request->post())) {
            // 获取用户输入的数据，验证并保存
            $model->create_time = date( 'Y-m-d H:i:s');
            //var_dump($model);
            if($model->save()){
                $this->redirect('/buyer/index');
            }
        }
        $platforms = Platform::find()->all();
        return $this->renderAjax('create',array(
            'model' => $model,
            'platforms' => $platforms
        ));
    }

    public function actionDelete($id){
        Buyer::findOne($id)->delete();
        $this->redirect('/buyer/index');
    }

    public function actionImport(){
        //$this->redirect('/buyer/index');
        $model = new Buyer();
        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->file->extension == 'xls') {
                $model->file->saveAs('uploads/up.xls');
                //require('/vendor/excel_reader2.php');
                require_once '../vendor/excel_reader2.php';
                $data = new Spreadsheet_Excel_Reader();
                //设置文本输出编码
                $data->setOutputEncoding('UTF-8');
                $data->read('uploads/up.xls');//读取excel
                $arr1=array();
                $arr2=array();
                $arr3=array();
                $arr4=array();
                $arr5=array();
                $arr6=array();
                $arr7=array();
                $arr8=array();
                for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
                    //显示每个单元格内容
                    $arr1[$data->sheets[0]['cells'][$i][1]]=$data->sheets[0]['cells'][$i][1];
                    $arr2[$data->sheets[0]['cells'][$i][1]]=$data->sheets[0]['cells'][$i][2];
                    $arr3[$data->sheets[0]['cells'][$i][1]]=$data->sheets[0]['cells'][$i][3];
                    $arr4[$data->sheets[0]['cells'][$i][1]]=$data->sheets[0]['cells'][$i][4];
                    $arr5[$data->sheets[0]['cells'][$i][1]]=$data->sheets[0]['cells'][$i][5];
                    $arr6[$data->sheets[0]['cells'][$i][1]]=empty($data->sheets[0]['cells'][$i][6])?'':$data->sheets[0]['cells'][$i][6];
                    $arr7[$data->sheets[0]['cells'][$i][1]]=$data->sheets[0]['cells'][$i][7];
                    $arr8[$data->sheets[0]['cells'][$i][1]]=$data->sheets[0]['cells'][$i][8];

                    $buyer = new Buyer();
                    $buyer->platform = $arr1[$data->sheets[0]['cells'][$i][1]];
                    $buyer->account = $arr2[$data->sheets[0]['cells'][$i][1]];
                    $buyer->pass = $arr3[$data->sheets[0]['cells'][$i][1]];
                    $buyer->pay_account = $arr4[$data->sheets[0]['cells'][$i][1]];
                    $buyer->pay_pass = $arr5[$data->sheets[0]['cells'][$i][1]];
                    $buyer->regarea = $arr6[$data->sheets[0]['cells'][$i][1]];
                    $buyer->regphone = $arr7[$data->sheets[0]['cells'][$i][1]];
                    $buyer->remark = $arr8[$data->sheets[0]['cells'][$i][1]];
                    $buyer->create_time = date( 'Y-m-d H:i:s');

                    $exists = Buyer::find()
                        ->where('account = :account',[':account' => $buyer->account])
                        ->one();
                    if(is_null($exists)){
                        $buyer->save();
                    }
                }
                $i=$i-2;
                echo "	<meta charset='utf-8'>";
                echo "<script>alert('成功导入".$i."个小号')</script>";
                //echo "<script>history.go(-1);</script>";
            } else {
                echo "	<meta charset='utf-8'>";
                echo "<script>alert('上传失败')</script>";
                echo "<script>history.go(-1);</script>";
            }
            return;
        }
        return $this->renderAjax('import', ['model' => $model]);
    }

    public function actionSylist(){
        $model = new Buyer();
        $query = (new Query())->select('*')->from(Buyer::tableName())->where('platform=:platform', [':platform' => '淘宝'])->andWhere('isSync=:isSync', [':isSync' => 0]);
        $pages = new Pagination(['totalCount' =>$query->count(), 'pageSize' => '20']);
        $query->limit($pages->limit)->offset($pages->offset);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,//Buyer::findBySql($sql),
            'pagination' => false,
        ]);

        $platforms = Platform::find()->all();
        return $this->render('sylist', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'platforms' => $platforms,
            'pages' => $pages
        ]);
    }

    public function  actionSyc(){
        $data = Yii::$app->request->post("data");
        echo $data;
        $url = 'http://www.taodaso.com/ajax/get/rate/?'.$data;
        require_once '../vendor/http.php';
        $http = new Http();
        $result = $http->http_get($url);
        echo $result;
        return;
    }

    public function actionSycsave($id){
        $credit = Yii::$app->request->post('credit');
        $regtime = Yii::$app->request->post('regtime');
        $id_verifi = Yii::$app->request->post('id_verifi');
        $regarea = Yii::$app->request->post('regarea');
        $month = Yii::$app->request->post('month') == ''? 0:Yii::$app->request->post('month');
        $week = Yii::$app->request->post('week') == ''?0:Yii::$app->request->post('week');
        $buyer = Buyer::findOne($id);
        if(!empty($buyer)){
            $buyer->credit = $credit;
            $buyer->regtime = $regtime;
            $buyer->id_verifi = $id_verifi;
            $buyer->regarea = $regarea;
            $buyer->month = $month;
            $buyer->week = $$week;
            $buyer->save();
            echo 1;
            return;
        }
        echo 0;
    }

    public function actionView($id){
        $model = Buyer::findOne($id);
        if(empty($model))
            throw new HttpException(404,'小号不存在！');
        if(empty($model->regarea))
            $model->regarea = '';
        if(empty($model->id_verifi))
            $model->id_verifi = '';
        if(empty($model->address))
            $model->address = '';
        return $this->render('view',array(
            'model' => $model
        ));
    }
} 