<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/6/3
 * Time: 15:19
 */
namespace app\controllers;
use app\base\BaseController;
use app\models\CommodityOrder;
use app\models\CommodityOrderDetail;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Query;
class CommodityOrderDetailController extends BaseController{

    public function actionIndex(){

        $query = (new Query())
            ->from(CommodityOrderDetail::tableName())
            ->where('1=1');
        if($this->user->rid == 4){
            $query->andWhere('uid = :uid',[':uid'=> $this->user->id]);
        }
        $query->orderBy('recommend_time desc');
        $pages = new Pagination(['totalCount' =>$query->count(), 'pageSize' => '20']);
        $query->limit($pages->limit)->offset($pages->offset);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,//Buyer::findBySql($sql),
            'pagination' => false,
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'pages' => $pages,
        ]);
    }

    public function actionView($id){
        $commodityOrderDetail = CommodityOrderDetail::findOne($id);
        if(empty($commodityOrderDetail))
            throw new HttpException(404,'查看失败，数据不存在！');
        $commodityOrder = CommodityOrder::findOne($commodityOrderDetail->coid);
        if(empty($commodityOrder))
            throw new HttpException(404,'查看失败，数据不存在！');
        return $this->render('view',[
            'model' => $commodityOrder
        ]);
    }
}