<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/14
 * Time: 10:21
 */
namespace app\controllers;
use app\models\CommodityOrder;
use app\models\CommodityOrderDetail;
use app\models\form\OrderSearchForm;
use app\models\Order;
use app\base\BaseController;
use app\models\Statistics;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Query;
use yii\web\HttpException;
use yii\web\MethodNotAllowedHttpException;
class OrderController extends BaseController {

    public function actionIndex(){
        $model = new OrderSearchForm();
        $model->load(Yii::$app->request->post());
        $query = (new Query())
            ->select('*')
            ->from(Order::tableName())
            ->where('1=1');
        if(!empty($model->buyer))
            $query->andWhere('buyer=:buyer', [':buyer' => $model->buyer]);
        if(!empty($model->shop))
            $query->andWhere('shop=:shop', [':shop' => $model->shop]);
        if(!empty($model->commodity))
            $query->andWhere('commodity_id like :commodity_id', [':commodity_id' => '%'.$model->commodity.'%']);
        if(!empty($model->order_no))
            $query->andWhere('order_no=:order_no', [':order_no' => $model->order_no]);
        if(!empty($model->btime))
            $query->andWhere('create_time >= :create_time',[':create_time' => $model->btime]);
        if(!empty($model->etime))
            $query->andWhere('create_time <= :create_time',[':create_time' => $model->etime]);
        if($this->user->rid == 4){
            $query->andWhere('uid = :uid',[':uid' => $this->user->id]);
        }
        $query->orderBy('create_time desc');
        $pages = new Pagination(['totalCount' =>$query->count(), 'pageSize' => '20']);
        $query->limit($pages->limit)->offset($pages->offset);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,//Buyer::findBySql($sql),
            'pagination' => false,
        ]);

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'pages' => $pages
        ]);
    }

    public function actionExportcsv(){

        $model = new OrderSearchForm();
        $model->load(Yii::$app->request->post());
        $query = (new Query())
            ->select('*')
            ->from(Order::tableName())
            ->where('1=1');
        if(!empty($model->buyer))
            $query->andWhere('buyer=:buyer', [':buyer' => $model->buyer]);
        if(!empty($model->shop))
            $query->andWhere('shop=:shop', [':shop' => $model->shop]);
        if(!empty($model->commodity))
            $query->andWhere('commodity_id like :commodity_id', [':commodity_id' => '%'.$model->commodity.'%']);
        if(!empty($model->order_no))
            $query->andWhere('order_no=:order_no', [':order_no' => $model->order_no]);
        if(!empty($model->btime))
            $query->andWhere('create_time >= :create_time',[':create_time' => $model->btime]);
        if(!empty($model->etime))
            $query->andWhere('create_time <= :create_time',[':create_time' => $model->etime]);
        if($this->user->rid == 4){
            $query->andWhere('uid = :uid',[':uid' => $this->user->id]);
        }
        $query->orderBy('create_time desc');
        $pages = new Pagination(['totalCount' =>$query->count(), 'pageSize' => '20']);
        $query->limit($pages->limit)->offset($pages->offset);
        $orders = $query->all();
        $info = array();
        foreach($orders as $order){
            $row = array();
            $row[] = $order['buyer'];
            $row[] = $order['shop'];
            $row[] = $order['commodity'];
            $row[] = $order['order_no'];
            $row[] = $order['money'];
            $row[] = $order['address'];
            $row[] = $order['order_time'];
            array_push($info, $row);
        }
        $detail = '';
        $subject =mb_convert_encoding("买家,店铺,商品,订单号,金额,收货地址,下单时间\n", "CP936", "UTF-8");
        foreach($info as $v) {
            foreach($v as $value) {
                $value = preg_replace('/\s+/', ' ', $value);
                $detail .= strlen($value) > 11 && is_numeric($value) ? '['.$value.'],' : $value.',';
            }
            $detail = $detail."\n";
        }

        $filename = time() . '_订单管理.csv';

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
        $model = new Order();
        if ($model->load(Yii::$app->request->post())) {
            $model->scenario = 'create';
            $model->create_time = date( 'Y-m-d H:i:s');
            $model->uid = $this->user->id;
            $model->statu = Order::$_AUDIT_PEND;
            $commodityOrderDetail = (new Query())
                ->select('*')
                ->from(CommodityOrder::tableName())
                ->leftJoin(CommodityOrderDetail::tableName(),CommodityOrderDetail::tableName().'.coid = '.CommodityOrder::tableName().'.id')
                ->where('commodity_id = :commodity_id and entrance = :entrance',[':commodity_id'=>$model->commodity,':entrance'=>$model->entrance])
                ->one();
            $model->shop = $commodityOrderDetail['shop'];
            $model->fee = $commodityOrderDetail['fee'];
            if($model->save()){
                echo "	<meta charset='utf-8'>";
                echo "<script>alert('提交成功')</script>";
                $model  = new Order();
            }
        }
        return $this->render('create',array(
            'model' => $model
        ));
    }

    public function actionGuestCreate(){
        $model = new Order();
        if ($model->load(Yii::$app->request->post())) {
            $model->scenario = 'create';
            $model->create_time = date( 'Y-m-d H:i:s');
            $model->uid = 0;
            $model->statu = Order::$_AUDIT_PEND;
            $commodityOrderDetail = (new Query())
                ->select('*')
                ->from(CommodityOrder::tableName())
                ->leftJoin(CommodityOrderDetail::tableName(),CommodityOrderDetail::tableName().'.coid = '.CommodityOrder::tableName().'.id')
                ->where('commodity_id = :commodity_id and entrance = :entrance',[':commodity_id'=>$model->commodity,':entrance'=>$model->entrance])
                ->one();
            $model->shop = $commodityOrderDetail['shop'];
            $model->fee = $commodityOrderDetail['fee'];
            if($model->save()){
                echo "	<meta charset='utf-8'>";
                echo "<script>alert('提交成功')</script>";
                $model  = new Order();
            }
        }
        return $this->render('create',array(
            'model' => $model
        ));
    }

    public function actionUpdate($id){
        $model = Order::findOne($id);
        if(empty($model))
            throw new HttpException(404,'订单不存在！');
        if($model->uid !=0 && $model->uid != $model->uid && $this->user->rid == 4)
            throw new MethodNotAllowedHttpException('权限不够，不能修改！');
        if($model->statu == Order::$_AUDIT_ACCESS)
            throw new MethodNotAllowedHttpException('已审核订单，不能修改！');
        if ($model->load(Yii::$app->request->post())) {
            $model->scenario = 'update';
            // 获取用户输入的数据，验证并保存
            $model->statu = Order::$_AUDIT_PEND;
            $commodityOrderDetail = (new Query())
                ->select(CommodityOrderDetail::tableName().'.*')
                ->from(CommodityOrder::tableName())
                ->leftJoin(CommodityOrderDetail::tableName(),CommodityOrderDetail::tableName().'.coid = '.CommodityOrder::tableName().'.id')
                ->where('commodity_id = :commodity_id and entrance = :entrance',[':commodity_id'=>$model->commodity,':entrance'=>$model->entrance])
                ->one();
            $model->fee = $commodityOrderDetail['fee'];
            if($model->save()){
                $this->redirect('/order/index');
            }
        }
        echo $model['commodity'];
        echo $model->shop;
        return $this->render('update',array(
            'model' => $model
        ));
    }

    public function actionDelete($id){
        $order = Order::findOne($id);
        if(empty($model))
            throw new HttpException(404,'订单不存在！');
        if($model->uid !=0 && $model->uid != $model->uid && $this->user->rid == 4)
            throw new MethodNotAllowedHttpException('权限不够，不能删除！');
        if($order->statu == Order::$_AUDIT_ACCESS)
            throw new MethodNotAllowedHttpException('已审核订单，不能删除！');
        $order->delete();
        $this->redirect('/order/index');
    }

    public function actionAudit(){
        $model = new OrderSearchForm();
        $model->load(Yii::$app->request->post());
        $query = (new Query())
            ->select('*')
            ->from(Order::tableName())
            ->where('1=1');
        if(!empty($model->buyer))
            $query->andWhere('buyer=:buyer', [':buyer' => $model->buyer]);
        if(!empty($model->shop))
            $query->andWhere('shop=:shop', [':shop' => $model->shop]);
        if(!empty($model->commodity))
            $query->andWhere('commodity_id like :commodity_id', [':commodity_id' => '%'.$model->commodity.'%']);
        if(!empty($model->order_no))
            $query->andWhere('order_no=:order_no', [':order_no' => $model->order_no]);
        if(!empty($model->btime))
            $query->andWhere('create_time >= :create_time',[':create_time' => $model->btime]);
        if(!empty($model->etime))
            $query->andWhere('create_time <= :create_time',[':create_time' => $model->etime]);
        $query->andWhere('statu = 0');
        $query->orderBy('create_time desc');
        $pages = new Pagination(['totalCount' =>$query->count(), 'pageSize' => '20']);
        $query->limit($pages->limit)->offset($pages->offset);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,//Buyer::findBySql($sql),
            'pagination' => false,
        ]);

        $audit_json = array();
        foreach(Order::$Audits as $key=>$value){
            $enarray = ['value'=>$key,'text'=>$value];
            array_push($audit_json,$enarray);
        }
        $audit_json = json_encode($audit_json);
        return $this->render('audit', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'pages' => $pages,
            'audit_json' => $audit_json
        ]);
    }

    public function actionUpdateAuditStatu(){
        $id = Yii::$app->request->post('pk');
        //$name = Yii::$app->request->post('name');
        $value = Yii::$app->request->post('value');
        $order = Order::findOne($id);
        if($order->statu != Order::$_AUDIT_PEND){
            $result = [
                'status' => 'error',
                'msg' => '该商品已审核！'
            ];
            echo json_encode($result);
            return;
        }
        $order->statu = $value;
        if($order->save()){
            $total_num = Order::find()->where('shop = :shop and commodity = :commodity and statu = :statu',[':shop'=>$order->shop,':commodity'=>$order->commodity,':statu'=>Order::$_AUDIT_ACCESS])->count();
            $fact_fee = Order::find()->where('shop = :shop and commodity = :commodity and statu = :statu',[':shop'=>$order->shop,':commodity'=>$order->commodity,':statu'=>Order::$_AUDIT_ACCESS])->sum('fee');
            $total_income = Order::find()->where('shop = :shop and commodity = :commodity and statu = :statu',[':shop'=>$order->shop,':commodity'=>$order->commodity,':statu'=>Order::$_AUDIT_ACCESS])->sum('money');

            $statistics =  Statistics::find()->where('shop = :shop and commodity = :commodity',[':shop'=>$order->shop,':commodity'=>$order->commodity])->one();
            if(!empty($statistics)){
                $statistics->total_num = $total_num;
                $statistics->fact_fee = $fact_fee;//intval($order->fee);
                $statistics->total_income = $total_income;//floatval($order->money);
                $statistics->save();
            }
        }
        $result = [
            'status' => 'sucess'
        ];
        echo json_encode($result);
    }

} 