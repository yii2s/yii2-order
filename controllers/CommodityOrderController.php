<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/11
 * Time: 20:50
 */
namespace app\controllers;
use app\models\Buyer;
use app\models\Commodity;
use app\models\CommodityOrder;
use app\models\CommodityOrderDetail;
use app\models\CommodityOrderTemplet;
use app\models\Entrance;
use app\models\form\CommoditySearchForm;
use app\base\BaseController;
use app\models\Statistics;
use app\models\User;
use PDO;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Query;
use yii\web\HttpException;
use yii\web\MethodNotAllowedHttpException;
class CommodityOrderController extends BaseController{

    public function actionIndex(){
        $model = new CommoditySearchForm();
        $model->load(Yii::$app->request->post());
        $query = (new Query())
            ->select(CommodityOrder::tableName().'.*')
            ->from(CommodityOrder::tableName())
            ->leftJoin(CommodityOrderDetail::tableName(),CommodityOrderDetail::tableName().'.coid = '.CommodityOrder::tableName().'.id')
            ->where('1=1');
        if(!empty($model->shop))
            $query->andWhere('shop=:shop', [':shop' => $model->shop]);
        if(!empty($model->commodity))
            $query->andWhere('commodity_id like :commodity_id', [':commodity_id' => '%'.$model->commodity.'%']);
        if(!empty($model->sku))
            $query->andWhere('sku=:sku', [':sku' => $model->sku]);
        if(!empty($model->btime))
            $query->andWhere(CommodityOrder::tableName().'.create_time >= :create_time',[':create_time' => $model->btime]);
        if(!empty($model->etime))
            $query->andWhere(CommodityOrder::tableName().'.create_time <= :create_time',[':create_time' => $model->etime]);
        if($model->op_statu != '')
            $query->andWhere(CommodityOrder::tableName().'.op_statu = :op_statu',[':op_statu' => $model->op_statu]);
        if($this->user->rid == 3){
            $query->andWhere(CommodityOrder::tableName().'.uid = :uid',[':uid'=> $this->user->id]);
        }
        $query->orderBy(CommodityOrder::tableName().'.create_time desc');
        $query->groupBy(CommodityOrder::tableName().'.id');
        $pages = new Pagination(['totalCount' =>$query->count(), 'pageSize' => '20']);
        $query->limit($pages->limit)->offset($pages->offset);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,//Buyer::findBySql($sql),
            'pagination' => false,
        ]);

        $ops = array();
        $enarray = ['value'=>'','text'=>'全部'];
        array_push($ops,$enarray);
        foreach(CommodityOrder::$Ops as $key=>$value){
            $enarray = ['value'=>$key,'text'=>$value];
            array_push($ops,$enarray);
        }
        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'pages' => $pages,
            'ops' => $ops
        ]);
    }

    public function actionCreate($cid){
        $commodity = Commodity::findOne($cid);
        if(empty($commodity))
            throw new HttpException(404,'商品不存在！');
        if($commodity->statu != Commodity::$_AUDIT_ACCESS)
            throw new HttpException(404,'商品未审核通过！');
        $model = new CommodityOrder();
        if ($model->load(Yii::$app->request->post())) {
            $model->create_time = date( 'Y-m-d H:i:s');
            $model->uid = $this->user->id;
            $model->statu = CommodityOrder::$_AUDIT_PEND;
            $model->op_statu = CommodityOrder::$_OP_NOT;
            $model->platform_id = $commodity->platform_id;
            if($model->save()){
                $commodityOrderDetails = Yii::$app->request->post('CommodityOrderDetail');
                $size = count($commodityOrderDetails['keyword']);
                $corpus = 0;
                $total_fee = 0;
                $budget_num = 0;
                for($i=0;$i<$size;$i++){
                    $commodityOrderDetail = new CommodityOrderDetail();
                    $commodityOrderDetail->coid = $model->id;
                    $commodityOrderDetail->keyword = $commodityOrderDetails['keyword'][$i];
                    $commodityOrderDetail->eid = $commodityOrderDetails['eid'][$i];
                    $entrance = Entrance::findOne($commodityOrderDetail->eid);
                    if(!is_null($entrance))
                        $commodityOrderDetail->entrance = $entrance->name;
                    $commodityOrderDetail->condition = $commodityOrderDetails['condition'][$i];
                    $commodityOrderDetail->num = $commodityOrderDetails['num'][$i];
                    $commodityOrderDetail->price = $commodityOrderDetails['price'][$i];
                    $commodityOrderDetail->fee = $commodityOrderDetails['fee'][$i];
                    $commodityOrderDetail->save();
                    $num = intval($commodityOrderDetail->num);
                    $price = floatval($commodityOrderDetail->price);
                    $fee = floatval($commodityOrderDetail->fee);
                    $total_price = $num * $price;
                    $sum_fee = $num * $fee;
                    $corpus += $total_price;
                    $total_fee += $sum_fee;
                    $budget_num += $num;
                }
                $statistics = new Statistics();
                $statistics->coid = $model->id;
                $statistics->shop = $model->shop;
                $statistics->platform = $model->platform;
                $statistics->commodity = $model->commodity_id;
                $statistics->corpus = $corpus;
                $statistics->total_fee = $total_fee;
                $statistics->real_income = 0;
                $statistics->fact_fee = 0;
                $statistics->total_num = 0;
                $statistics->total_income = 0;
                $statistics->budget_num = $budget_num;
                $statistics->handle_time = $model->handle_time;
                $statistics->uid = $model->uid;
                $statistics->save();
                echo "	<meta charset='utf-8'>";
                echo "<script>alert('提交成功！')</script>";
                echo "<script>history.go(-1);</script>";
                return;
            }

        }
        $model->cid = $cid;
        $model->shop = $commodity->shop;
        $model->commodity_id = $commodity->commodity_id;
        $model->sku = $commodity->sku;
        $model->img = $commodity->img;
        $model->rule = $commodity->rule;
        $model->buyer_rule = $commodity->buyer_rule;
        $model->remark = $commodity->remark;
        $model->platform = $commodity->platform;
        $model->credit = $commodity->credit;
        $model->trade_num = $commodity->trade_num;

        $dataProvider = new ActiveDataProvider([
            'query' => CommodityOrderTemplet::find()->where('cid = :cid',[':cid'=>$cid])->orderBy('keyword'),
            'pagination' => false,
        ]);

        return $this->render('create',array(
            'model' => $model,
            'commodity' => $commodity,
            'dataProvider' => $dataProvider
        ));
    }

    public function actionDelete($id){
        $commodityOrder = CommodityOrder::findOne($id);
        if(empty($commodityOrder))
            throw new HttpException(404,'删除失败，放单不存在！');
        if($this->user->rid != 1 && $commodityOrder->uid != $this->user->id){
            throw new HttpException(404,'error');
        }
        if($commodityOrder->statu == CommodityOrder::$_AUDIT_ACCESS)
            throw new MethodNotAllowedHttpException('已审核放单，不能删除！');
        CommodityOrderDetail::deleteAll('coid = :coid', [':coid' => $id]);
        $commodityOrder->delete();
        $this->redirect('/commodity-order/index');
    }

    public function actionView($id){
        $model = CommodityOrder::findOne($id);
        if(empty($model))
            throw new HttpException(404,'放单不存在！');
        if($this->user->rid != 1 && $model->uid != $this->user->id){
            throw new HttpException(404,'error');
        }
        $dataProvider = new ActiveDataProvider([
            'query' => CommodityOrderDetail::find()->where('coid = :coid and num <> 0',[':coid'=>$model->id])->orderBy('keyword'),
            'pagination' => false,
        ]);

        return $this->render('view',array(
            'model' => $model,
            'dataProvider' => $dataProvider
        ));
    }

    public function actionUpdate($id){
        $model = CommodityOrder::findOne($id);
        if(empty($model))
            throw new HttpException(404,'操作失败，放单不存在！');
        if($this->user->rid != 1 && $model->uid != $this->user->id){
            throw new HttpException(404,'error');
        }
        if($model->statu == CommodityOrder::$_AUDIT_ACCESS)
            throw new MethodNotAllowedHttpException('已审核放单，不能修改！');

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){

                $statistics = Statistics::find()->where('coid = :coid',[':coid'=>$model->id])->one();
                $statistics->handle_time = $model->handle_time;
                $statistics->save();

                echo "	<meta charset='utf-8'>";
                echo "<script>alert('保存成功！')</script>";
                echo "<script>history.go(-1);</script>";
                return;
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => CommodityOrderDetail::find()->where('coid = :coid',[':coid' => $model->id])->orderBy('keyword'),
            'pagination' => false,
        ]);

        $entrances = Entrance::find()->all();
        $entrances_json = array();
        foreach($entrances as $entrance){
            $enarray = ['value'=>$entrance->id,'text'=>$entrance->name];
            array_push($entrances_json,$enarray);
        }
        $entrances_json = json_encode($entrances_json);
        return $this->render('update',array(
            'model' => $model,
            'dataProvider' => $dataProvider,
            'entrances' => $entrances,
            'entrances_json'=>$entrances_json
        ));
    }

    public function actionUpdateOrderDetail(){
        $id = Yii::$app->request->post('pk');
        $name = Yii::$app->request->post('name');
        $value = Yii::$app->request->post('value');
        $commodityOrderDetail = CommodityOrderDetail::findOne($id);
        $commodityOrderDetail->$name = $value;
        if($name == 'eid'){
            $entrance = Entrance::findOne($value);
            if(!empty($entrance)){
                $commodityOrderDetail->entrance = $entrance->name;
            }
        }
        if($name == 'recommends'){
            if($value == '0')
                $commodityOrderDetail->recommends = '';
            else if($value == '1'){
                $commodityOrder = CommodityOrder::findOne($commodityOrderDetail->coid);
                $sql = 'SELECT t.id FROM (SELECT `tbl_buyer`.`id`,COUNT(*) AS num FROM `tbl_buyer` RIGHT JOIN `tbl_order` ON `tbl_buyer`.`account` = `tbl_order`.`buyer` WHERE `tbl_buyer`.`platform` = :platform AND `tbl_buyer`.`credit_temp` >= :credit_temp  AND date_sub(curdate(), INTERVAL 30 DAY) <= date(`tbl_buyer`.`buy_time`) ORDER BY `tbl_buyer`.`weight` DESC,RAND()) AS t WHERE t.num >= :trade_num LIMIT :num';
                $db = Yii::$app->db;
                $command=$db->createCommand($sql);
                $command->bindValue(":platform",$commodityOrder->platform);
                $command->bindValue(":credit_temp",$commodityOrder->credit);
                $command->bindValue(":trade_num",$commodityOrder->trade_num);
                $command->bindValue(":num",$commodityOrderDetail->num);
                $buyerArray = $command->queryColumn();
//                $buyers = Buyer::find()->where('platform = :platform and  and date_sub(curdate(), INTERVAL 30 DAY) <= date(buy_time)',[':platform'=>$commodityOrder->platform])->orderBy('weight desc,rand()')->limit($commodityOrderDetail->num)->all();
//                $buyerArray = array();
//                foreach($buyers as $buyer){
//                    $buyerArray[] = $buyer->id;
//                }
                $ids = '';
                if(!empty($buyerArray))
                    $ids = implode(',',$buyerArray);
                if(empty($ids)){
                    $result = [
                        'status' => 'error',
                        'msg' => '系统推荐失败，系统没找到符合要求的买家帐号'
                    ];
                    echo json_encode($result);
                    return;
                }
                $commodityOrderDetail->recommends = $ids;
            }
        }
        if($name == 'uid'){
            $commodityOrderDetail->recommend_time = date( 'Y-m-d H:i:s');
        }
        if($commodityOrderDetail->save()){
            $commodityOrderDetails = CommodityOrderDetail::find()->where('coid = :coid',[':coid'=>$commodityOrderDetail->coid])->all();
            $corpus = 0;
            $total_fee = 0;
            $budget_num = 0;
            foreach($commodityOrderDetails as $cod){
                $num = intval($cod->num);
                $price = floatval($cod->price);
                $fee = floatval($cod->fee);
                $total_price = $num * $price;
                $sum_fee = $num * $fee;
                $corpus += $total_price;
                $total_fee += $sum_fee;
                $budget_num += $num;
            }
            $statistics =  Statistics::find()->where('coid = :coid',[':coid'=>$commodityOrderDetail->coid])->one();
            $statistics->corpus = $corpus;
            $statistics->total_fee = $total_fee;
            $statistics->budget_num = $budget_num;
            $statistics->save();
        }
        $result = [
            'status' => 'sucess'
        ];
        echo json_encode($result);
    }

    public function actionAudit(){
        $model = new CommoditySearchForm();
        $model->load(Yii::$app->request->post());
        $query = (new Query())
            ->select(CommodityOrder::tableName().'.*')
            ->from(CommodityOrder::tableName())
            ->leftJoin(CommodityOrderDetail::tableName(),CommodityOrderDetail::tableName().'.coid = '.CommodityOrder::tableName().'.id')
            ->where('1=1');
        if(!empty($model->shop))
            $query->andWhere('shop=:shop', [':shop' => $model->shop]);
        if(!empty($model->commodity))
            $query->andWhere('commodity_id like :commodity_id', [':commodity_id' => '%'.$model->commodity.'%']);
        if(!empty($model->sku))
            $query->andWhere('sku=:sku', [':sku' => $model->sku]);
        if(!empty($model->btime))
            $query->andWhere(CommodityOrder::tableName().'.create_time >= :create_time',[':create_time' => $model->btime]);
        if(!empty($model->etime))
            $query->andWhere(CommodityOrder::tableName().'.create_time <= :create_time',[':create_time' => $model->etime]);
        if(!empty($model->statu))
            $query->andWhere('statu = :statu'.[':statu'=>$model->statu]);
        $query->orderBy(CommodityOrder::tableName().'.create_time desc');
        $query->groupBy(CommodityOrder::tableName().'.id');
        $pages = new Pagination(['totalCount' =>$query->count(), 'pageSize' => '20']);
        $query->limit($pages->limit)->offset($pages->offset);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,//Buyer::findBySql($sql),
            'pagination' => false,
        ]);

        $audit_json = array();
        $ops = array();
        $enarray = ['value'=>'','text'=>'全部'];
        array_push($ops,$enarray);
        foreach(CommodityOrder::$Audits as $key=>$value){
            $enarray = ['value'=>$key,'text'=>$value];
            array_push($audit_json,$enarray);
            array_push($ops,$enarray);
        }
        $audit_json = json_encode($audit_json);

        return $this->render('audit', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'pages' => $pages,
            'audit_json' => $audit_json,
            'ops' => $ops
        ]);
    }

    public function actionUpdateAuditStatu(){
        $id = Yii::$app->request->post('pk');
        //$name = Yii::$app->request->post('name');
        $value = Yii::$app->request->post('value');
        $commodityOrder = CommodityOrder::findOne($id);
        if($commodityOrder->statu != CommodityOrder::$_AUDIT_PEND){
            $result = [
                'status' => 'error',
                'msg' => '该商品已审核！'
            ];
            echo json_encode($result);
            return;
        }
        $commodityOrder->statu = $value;
        if($value == CommodityOrder::$_AUDIT_ACCESS)
            $commodityOrder->op_statu = CommodityOrder::$_OP_IN;
        $commodityOrder->save();
        $result = [
            'status' => 'sucess'
        ];
        echo json_encode($result);
    }

    public function actionUpdateRealIncome(){
        $id = Yii::$app->request->post('pk');
        //$name = Yii::$app->request->post('name');
        $value = Yii::$app->request->post('value');
        $commodityOrder = CommodityOrder::findOne($id);
        $commodityOrder->real_income = $value;
        if($commodityOrder->save()){
            $statistics = Statistics::find()->where('coid = :coid',[':coid'=>$commodityOrder->id])->one();
            $statistics->real_income = $value;
            $statistics->save();
        }
        $result = [
            'status' => 'sucess'
        ];
        echo json_encode($result);
    }

    public function actionAllot($id){
        $model = CommodityOrder::findOne($id);
        if(empty($model))
            throw new HttpException(404,'操作失败，放单不存在！');
        $dataProvider = new ActiveDataProvider([
            'query' => CommodityOrderDetail::find()->where('coid = :coid',[':coid'=>$model->id])->orderBy('keyword'),
            'pagination' => false,
        ]);

        $users = User::find()->where('rid = 4')->all();
        $users_json = array();
        $enarray = ['value'=>'','text'=>'未分配'];
        array_push($users_json,$enarray);
        foreach($users as $user){
            $enarray = ['value'=>$user->id,'text'=>$user->username];
            array_push($users_json,$enarray);
        }
        $users_json = json_encode($users_json);

        $recommend_json = [
            ['value'=>'1','text'=>'是'],
            ['value'=>'0','text'=>'否'],
        ];
        $recommend_json = json_encode($recommend_json);
        return $this->render('allot',array(
            'dataProvider' => $dataProvider,
            'users_json' => $users_json,
            'recommend_json' => $recommend_json
        ));
    }

} 