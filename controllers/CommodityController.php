<?php
/**
 * Created by PhpStorm.
 * User: vilison
 * Date: 2015/5/7
 * Time: 18:50
 */
namespace app\controllers;
use app\models\Commodity;
use app\models\CommodityOrderTemplet;
use app\models\Entrance;
use app\models\form\CommoditySearchForm;
use app\models\Platform;
use app\models\Shop;
use app\base\BaseController;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Query;
use yii\web\HttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\UploadedFile;
class CommodityController extends BaseController {

    public function actionIndex(){
        $model = new CommoditySearchForm();
        $model->load(Yii::$app->request->post());
        $query = (new Query())
            ->select(Commodity::tableName().'.*')
            ->from(Commodity::tableName())
            ->leftJoin(CommodityOrderTemplet::tableName(),CommodityOrderTemplet::tableName().'.cid = '.Commodity::tableName().'.id')
            ->where('1=1');
        if(!empty($model->shop))
            $query->andWhere('shop=:shop', [':shop' => $model->shop]);
        if(!empty($model->commodity))
            $query->andWhere('commodity_id like :commodity_id', [':commodity_id' => '%'.$model->commodity.'%']);
        if(!empty($model->sku))
            $query->andWhere('sku=:sku', [':sku' => $model->sku]);
        if(!empty($model->btime))
            $query->andWhere(Commodity::tableName().'.create_time >= :create_time',[':create_time' => $model->btime]);
        if(!empty($model->etime))
            $query->andWhere(Commodity::tableName().'.create_time <= :create_time',[':create_time' => $model->etime]);
        if($this->user->rid == 3){
//            $shops = Shop::find()->where('uid = :uid',[':uid'=>$this->user->id])->all();
//            if(count($shops) > 0){
//                $sids = array();
//                foreach($shops as $shop){
//                    $sids[] = $shop->id;
//                }
//                $sidsStr = implode(',',$sids);
//                $query->andWhere('shop_id in (:shop_id)',[':shop_id'=> $sidsStr]);
//            }
            $query->andWhere('uid = :uid',[':uid'=> $this->user->id]);
        }
        $query->orderBy(Commodity::tableName().'.create_time desc');
        $query->groupBy(Commodity::tableName().'.id');
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

    public function actionCreate(){
        $model = new Commodity();
        if ($model->load(Yii::$app->request->post())) {
            $model->scenario = 'create';
            // 获取用户输入的数据，验证并保存
            $model->create_time = date( 'Y-m-d H:i:s');
            //var_dump($model);
            $model->img = UploadedFile::getInstance($model, 'img');
            $name = time(). '.' . $model->img->extension;
            $model->img->saveAs('uploads/images/'.$name);
            $model->img = '/uploads/images/'.$name;
            $model->uid = $this->user->id;
            $model->statu = Commodity::$_AUDIT_PEND;
            $platform = Platform::findOne($model->platform_id);
            $model->platform = $platform->name;
            $shop = Shop::findOne($model->shop_id);
            if(!empty($shop))
                $model->shop = $shop->shop_name;
            if($model->save()){
                $this->redirect('/commodity/index');
            }else{
                var_dump($model->errors);
            }
        }
        $shops = array();
        if($this->user->rid == 3){
            $shops = Shop::find()->where('uid = :uid',[':uid'=>$this->user->id])->all();
        }else{
            $shops = Shop::find()->all();
        }
        $platforms = Platform::find()->all();
        //$shops = Shop::findAll('uid = :uid',[':uid'=>'']);

        return $this->render('create',array(
            'model' => $model,
            'shops' => $shops,
            'platforms' => $platforms
        ));
    }

    public function actionView($id){
        $model = Commodity::findOne($id);
        if(empty($model))
            throw new HttpException(404,'商品不存在！');
        if($this->user->rid != 1 && $model->uid != $this->user->id){
            throw new HttpException(404,'error');
        }
        $commodityOrderTemplet = new CommodityOrderTemplet();
        if($commodityOrderTemplet->load(Yii::$app->request->post())){
            $commodityOrderTemplet->cid = $model->id;
            $entrance = Entrance::findOne($commodityOrderTemplet->eid);
            if(!empty($entrance))
                $commodityOrderTemplet->entrance = $entrance->name;
            $commodityOrderTemplet->save();
            //$commodityOrderTemplet->dirtyAttributes;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => CommodityOrderTemplet::find()->where('cid = :cid',[':cid'=>$id])->orderBy('keyword'),
            'pagination' => false,
        ]);
        $entrances = Entrance::find()->all();
        $entrances_json = array();
        foreach($entrances as $entrance){
            $enarray = ['value'=>$entrance->id,'text'=>$entrance->name];
            array_push($entrances_json,$enarray);
        }
        $entrances_json = json_encode($entrances_json);
        return $this->render('view',array(
            'model' => $model,
            'dataProvider' => $dataProvider,
            'commodityOrderTemplet' => $commodityOrderTemplet,
            'entrances' => $entrances,
            'entrances_json'=>$entrances_json
        ));
    }

    public function actionUpdateOrderTemplet(){
        $id = Yii::$app->request->post('pk');
        $name = Yii::$app->request->post('name');
        $value = Yii::$app->request->post('value');
        $commodityOrderTemplet = CommodityOrderTemplet::findOne($id);
        $commodity = Commodity::findOne($commodityOrderTemplet->cid);
//        if($commodity->statu == Commodity::$_AUDIT_ACCESS){
//            $result = [
//                'status' => 'error',
//                'msg' => '商品已审核通过，修改失败！'
//            ];
//            echo json_encode($result);
//            return;
//        }
        $commodityOrderTemplet->$name = $value;
        if($name == 'eid'){
            $entrance = Entrance::findOne($value);
            if(!empty($entrance)){
                $commodityOrderTemplet->entrance = $entrance->name;
            }
        }
        $commodityOrderTemplet->save();
        $result = [
            'status' => 'sucess'
        ];
        echo json_encode($result);
    }

    public function actionDeleteOrderTemplet($id,$cid){
        CommodityOrderTemplet::findOne($id)->delete();
        $this->redirect('/commodity/view?id='.$cid);
    }

    public function actionDelete($id){
        $commodity = Commodity::findOne($id);
        if(empty($commodity))
            throw new HttpException(404,'删除失败，商品不存在！');
        if($this->user->rid != 1 && $commodity->uid != $this->user->id){
            throw new HttpException(404,'error');
        }
        if($commodity->statu == Commodity::$_AUDIT_ACCESS)
            throw new MethodNotAllowedHttpException('已审核商品，不能删除！');
        CommodityOrderTemplet::deleteAll('cid = :cid', [':cid' => $id]);
        $commodity->delete();
        $this->redirect('/commodity/index');
    }

    public function actionUpdate($id){
        $model = Commodity::findOne($id);
        if(empty($model))
            throw new HttpException(404,'操作失败，商品不存在！');
        if($this->user->rid != 1 && $model->uid != $this->user->id){
            throw new HttpException(404,'error');
        }
        $oimg = $model->img;
        if ($model->load(Yii::$app->request->post())) {
            $img = UploadedFile::getInstance($model, 'img');
            if(!is_null($img)){
                $model->img = $img;
                $name = time(). '.' . $model->img->extension;
                $model->img->saveAs('uploads/images/'.$name);
                $model->img = '/uploads/images/'.$name;
            }else{
                $model->img = $oimg;
            }
            $platform = Platform::findOne($model->platform_id);
            $model->platform = $platform->name;
            $shop = Shop::findOne($model->shop_id);
            if(!empty($shop))
                $model->shop = $shop->shop_name;
            $model->statu = Commodity::$_AUDIT_PEND;
            if($model->save()){
                $this->redirect('/commodity/index');
            }else{
                var_dump($model->errors);
            }
        }
        $shops = array();
        if($this->user->rid == 3){
            $shops = Shop::find()->where('uid = :uid',[':uid'=>$this->user->id])->all();
        }else{
            $shops = Shop::find()->all();
        }
        $platforms = Platform::find()->all();
        return $this->render('update',array(
            'model' => $model,
            'shops' => $shops,
            'platforms' => $platforms
        ));
    }

    public function actionAudit(){
        $model = new CommoditySearchForm();
        $model->load(Yii::$app->request->post());
        $query = (new Query())
            ->select(Commodity::tableName().'.*')
            ->from(Commodity::tableName())
            ->leftJoin(CommodityOrderTemplet::tableName(),CommodityOrderTemplet::tableName().'.cid = '.Commodity::tableName().'.id')
            ->where('1=1');
        if(!empty($model->shop))
            $query->andWhere('shop=:shop', [':shop' => $model->shop]);
        if(!empty($model->commodity))
            $query->andWhere('commodity_id like :commodity_id', [':commodity_id' => '%'.$model->commodity.'%']);
        if(!empty($model->sku))
            $query->andWhere('sku=:sku', [':sku' => $model->sku]);
        if(!empty($model->btime))
            $query->andWhere(Commodity::tableName().'.create_time >= :create_time',[':create_time' => $model->btime]);
        if(!empty($model->etime))
            $query->andWhere(Commodity::tableName().'.create_time <= :create_time',[':create_time' => $model->etime]);
        $query->andWhere('statu = 0');
        $query->orderBy(Commodity::tableName().'.create_time desc');
        $query->groupBy(Commodity::tableName().'.id');
        $pages = new Pagination(['totalCount' =>$query->count(), 'pageSize' => '20']);
        $query->limit($pages->limit)->offset($pages->offset);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,//Buyer::findBySql($sql),
            'pagination' => false,
        ]);

        $audit_json = array();
        foreach(Commodity::$Audits as $key=>$value){
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
        $commodity = Commodity::findOne($id);
        if($commodity->statu != Commodity::$_AUDIT_PEND){
            $result = [
                'status' => 'error',
                'msg' => '该商品已审核！'
            ];
            echo json_encode($result);
            return;
        }
        $commodity->statu = $value;
        $commodity->save();
        $result = [
            'status' => 'sucess'
        ];
        echo json_encode($result);
    }

    public function actionAuditView($id){
        $model = Commodity::findOne($id);
        $commodityOrderTemplet = new CommodityOrderTemplet();
        if($commodityOrderTemplet->load(Yii::$app->request->post())){
            $commodityOrderTemplet->cid = $model->id;
            $entrance = Entrance::findOne($commodityOrderTemplet->eid);
            if(!empty($entrance))
                $commodityOrderTemplet->entrance = $entrance->name;
            $commodityOrderTemplet->save();
            //$commodityOrderTemplet->dirtyAttributes;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => CommodityOrderTemplet::find()->where('cid = :cid',[':cid'=>$id])->orderBy('keyword'),
            'pagination' => false,
        ]);
        $entrances = Entrance::find()->all();
        $entrances_json = array();
        foreach($entrances as $entrance){
            $enarray = ['value'=>$entrance->id,'text'=>$entrance->name];
            array_push($entrances_json,$enarray);
        }
        $entrances_json = json_encode($entrances_json);
        return $this->render('audit-view',array(
            'model' => $model,
            'dataProvider' => $dataProvider,
            'commodityOrderTemplet' => $commodityOrderTemplet,
            'entrances' => $entrances,
            'entrances_json'=>$entrances_json
        ));
    }


    public static function pageTotalPrice($provider, $fieldName){
        $total = 0.00;
        foreach($provider as $item){
            $total = floatval($total) + floatval($item[$fieldName]);
        }
        return $total;
    }

    public static function pageTotalNumPrice($provider, $fieldName,$num){
        $total = 0.00;
        foreach($provider as $item){
            $total = floatval($total) + floatval($item[$num]*$item[$fieldName]);
        }
        return $total;
    }

    public static function pageTotalPrices($provider,$price,$fee,$num){
        $total = 0.00;
        foreach($provider as $item){
            $total = floatval($total) + floatval($item[$num]*$item[$price]) + floatval($item[$num]*$item[$fee]);
        }
        return $total;
    }

    public static function pageTotal($provider,$fieldName){
        $total = 0;
        foreach($provider as $item){
            $total += $item[$fieldName];
        }
        return $total;
    }

    public static function pageTotalNum($provider,$fieldName){
        $total = 0;
        foreach($provider as $item){
            $total += $item[$fieldName];
        }
        return $total;
    }

} 