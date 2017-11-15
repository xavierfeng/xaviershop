<?php
namespace frontend\controllers;

use backend\models\ArticleCategory;
use backend\models\GoodsCategory;
use frontend\models\Address;
use yii\web\Controller;
use yii\web\Request;

class AddressController extends Controller
{
    public  $enableCsrfValidation = false;
    //地址列表
    public function actionIndex()
    {
        $article_categorys= ArticleCategory::find()->all();
        $addresses = Address::find()->where(["member_id"=>\Yii::$app->user->getId()])->orderBy(['status'=>SORT_DESC])->all();
        $goods_categorys1=GoodsCategory::find()->where(['parent_id'=>0])->all();
        return $this->render('index',['addresses'=>$addresses,'goods_categorys1'=>$goods_categorys1,'article_categorys'=>$article_categorys]);
    }
    //添加地址
    public function actionAdd()
    {
        $address= new Address();
        $request= new Request();
        if($request->isPost){
            $address->load($request->post(),'');
            if($address->validate()){
                $address->member_id=\Yii::$app->user->getId();
                if($address->status=1){
                    $defaultAddress=Address::findOne(['status'=>1]);
                    $defaultAddress->status=0;
                    $defaultAddress->save();
                }
                $address->save();
                return "success";
            }else{
                return "false";
            }
        }
    }

    //修改地址
    public function actionUpdate($id)
    {
        $article_categorys= ArticleCategory::find()->all();
        $address= Address::findOne(['id'=>$id]);
        $request= new Request();
        $goods_categorys1=GoodsCategory::find()->where(['parent_id'=>0])->all();
        if($request->isPost){
            $address->load($request->post(),'');
            if($address->validate()){
                $address->save();
                return $this->redirect(['address/index']);
            }
        }else{
            return $this->render('edit',['address'=>$address,'goods_categorys1'=>$goods_categorys1,'article_categorys'=>$article_categorys]);
        }

    }

    //删除地址
    public function actionDelete()
    {
        $address = Address::findOne(['id'=>\Yii::$app->request->post('id')]);
        if($address){
            $address->delete();
            return "success";
        }
    }

    //修改默认地址
    public function actionDefaultAddress()
    {
        $address= Address::findOne(['id'=>\Yii::$app->request->post('id')]);
        if($address){
            $defaultAddress=Address::findOne(['status'=>1]);
            $defaultAddress->status=0;
            $defaultAddress->save();
            $address->status=1;
            $address->save();
            return "success";
        }else{
            return false;
        }
    }
}