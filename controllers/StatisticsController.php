<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/6/2
 * Time: 19:06
 */
namespace app\controllers;
use app\base\BaseController;
use app\models\form\StatisticsSearchForm;
use app\models\Platform;
use app\models\Statistics;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Query;
class StatisticsController extends BaseController{

    public function actionIndex(){
        $model = new StatisticsSearchForm();
        $model->load(Yii::$app->request->post());
        $query = (new Query())
            ->from(Statistics::tableName())
            ->where('1=1');
        if(!empty($model->shop))
            $query->andWhere('shop=:shop', [':shop' => $model->shop]);
        if(!empty($model->commodity))
            $query->andWhere('commodity like :commodity', [':commodity' => '%'.$model->commodity.'%']);
        if(!empty($model->platform)){
            $query->andWhere('platform=:platform', [':platform' => $model->platform]);
        }
        if(!empty($model->btime))
            $query->andWhere('handle_time >= :handle_time',[':handle_time' => $model->btime]);
        if(!empty($model->etime))
            $query->andWhere('handle_time <= :handle_time',[':handle_time' => $model->etime]);
        if($this->user->rid == 3){
            $query->andWhere('uid = :uid',[':uid'=> $this->user->id]);
        }
        $query->orderBy('handle_time desc');

        $pages = new Pagination(['totalCount' =>$query->count(), 'pageSize' => '20']);
        $query->limit($pages->limit)->offset($pages->offset);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,//Buyer::findBySql($sql),
            'pagination' => false,
        ]);

        $platforms = Platform::find()->all();
        $ops = array();
        $enarray = ['value'=>'','text'=>'全部'];
        array_push($ops,$enarray);
        foreach($platforms as $platform){
            $enarray = ['value'=>$platform->name,'text'=>$platform->name];
            array_push($ops,$enarray);
        }
        $page = 'index';
        if($this->user->rid == 4)
            $page = 'index-4';
        return $this->render($page, [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'pages' => $pages,
            'ops' => $ops
        ]);
    }

    public function actionExportcsv(){

        $model = new StatisticsSearchForm();
        $model->load(Yii::$app->request->post());
        $query = (new Query())
            ->from(Statistics::tableName())
            ->where('1=1');
        if(!empty($model->shop))
            $query->andWhere('shop=:shop', [':shop' => $model->shop]);
        if(!empty($model->commodity))
            $query->andWhere('commodity like :commodity', [':commodity' => '%'.$model->commodity.'%']);
        if(!empty($model->platform)){
            $query->andWhere('platform=:platform', [':platform' => $model->platform]);
        }
        if(!empty($model->btime))
            $query->andWhere('handle_time >= :handle_time',[':handle_time' => $model->btime]);
        if(!empty($model->etime))
            $query->andWhere('handle_time <= :handle_time',[':handle_time' => $model->etime]);
        if($this->user->rid == 3){
            $query->andWhere('uid = :uid',[':uid'=> $this->user->id]);
        }
        $query->orderBy('handle_time desc');

        $pages = new Pagination(['totalCount' =>$query->count(), 'pageSize' => '20']);
        $query->limit($pages->limit)->offset($pages->offset);

        $statisticss = $query->all();
        $info = array();
        foreach($statisticss as $statistics){
            $row = array();
            $row[] = $statistics['shop'];
            $row[] = $statistics['platform'];
            $row[] = $statistics['commodity'];
            $row[] = $statistics['corpus'];
            if($this->user->rid != 4)
                $row[] = $statistics['real_income'];
            $row[] = $statistics['total_income'];
            if($this->user->rid != 4)
                $row[] = $statistics['total_fee'];
            $row[] = $statistics['corpus'] - $statistics['total_income'];
            $row[] = $statistics['total_num'];
            $row[] = $statistics['budget_num'];
            $row[] = $statistics['handle_time'];
            array_push($info, $row);
        }
        $detail = '';
        $subject =mb_convert_encoding("店铺,平台,商品,本金,已收金额,差额,提交笔数,商家笔数,执行时间\n", "CP936", "UTF-8");
        if($this->user->rid != 4)
            $subject =mb_convert_encoding("店铺,平台,商品,本金,实收金额,已收金额,总佣金,差额,提交笔数,商家笔数,执行时间\n", "CP936", "UTF-8");
        foreach($info as $v) {
            foreach($v as $value) {
                $value = preg_replace('/\s+/', ' ', $value);
                $detail .= strlen($value) > 11 && is_numeric($value) ? '['.$value.'],' : $value.',';
            }
            $detail = $detail."\n";
        }

        $filename = time() . '_报表统计.csv';

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
}