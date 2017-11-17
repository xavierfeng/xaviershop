<?php
namespace frontend\controllers;

use backend\models\ArticleCategory;
use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Cart;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Request;

class GoodsController extends Controller
{

    public $enableCsrfValidation=false;

    //分类列表
    public function actionList()
    {
        $id=\Yii::$app->request->get('id');
        //文章分类
        $article_categorys= ArticleCategory::find()->all();
        //商品分类
        $html=GoodsCategory::getGoodsCategorys();
        //获取商品分类链接商品列表
        $goods_category=GoodsCategory::findOne(['id'=>$id]);
        if($goods_category->depth == 2){
            $query = Goods::find()->where(['goods_category_id'=>$id]);
        }else{
            $ids = $goods_category->children()->andWhere(['depth'=>2])->column();
            $query = Goods::find()->where(['in','goods_category_id',$ids]);
        }
        $goodsList = $query->all();
        return $this->render('list',['goodsList'=>$goodsList,'html'=>$html,'goods_category'=>$goods_category,'article_categorys'=>$article_categorys]);
    }

    //商品详情页
    public function actionGoods()
    {
        $html=GoodsCategory::getGoodsCategorys();
        $id=\Yii::$app->request->get('id');
        $article_categorys= ArticleCategory::find()->all();
        $goods= Goods::find()->where(['id'=>$id])->one();
        return $this->render('goods',['goods'=>$goods,'html'=>$html,'article_categorys'=>$article_categorys]);
    }

    //添加成功提示页将商品添加到购物车
    public function actionAddCart($goods_id,$amount)
    {
        if(\Yii::$app->user->isGuest) {
            //未登录,购物车数据存放在cookie
            //先取出cookie中购物车数据
            //读取cookie
            $cookies=\Yii::$app->request->cookies;
            $carts=$cookies->getValue('carts');
            if($carts){
                $carts= unserialize($carts);
            }else{
                $carts=[];
            }
            //判断购物车中是否有该商品 如果存在数量累加,不存在添加
            if(array_key_exists($goods_id,$carts)){
                $carts[$goods_id]+=$amount;
            }else{
                //goods_id作为key,amount作为value['1'=>'3']
                $carts[$goods_id]=$amount;
            }
            //写入cookie
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookie->expire=time()+3600*7*24;
            $cookies->add($cookie);
        }else{
            //操作数据库的购物车
            //已登录,购物车存放在数据表
            $request = new Request();
            $member_id=\Yii::$app->user->id;
            $goods_id=$request->get('goods_id');
                //判断该用户购物车中是否已经有该商品
                if($cart=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id])){
                    $cart->amount+=$request->get('amount');
                }else{
                    $cart = new Cart();
                    $cart->goods_id=$goods_id;
                    $cart->member_id=$member_id;
                    $cart->load($request->get(),'');
                }
                if($cart->validate()){
                    $cart->save();
                }
        }

        return $this->redirect(['cart']);
    }

    //购物车列表页
    public function actionCart()
    {
        //判断登录和未登录
        if(\Yii::$app->user->isGuest){
            //用户未登录
            //读取cookie
            $cookies=\Yii::$app->request->cookies;
            $carts=$cookies->getValue('carts');
            if($carts){
                $carts= unserialize($carts);
            }else{
                $carts=[];
            }

        }else{
            //用户已经登录
            $member_id=\Yii::$app->user->id;
            $carts=Cart::find()->where(['member_id'=>$member_id])->all();
            $carts=ArrayHelper::map($carts,'goods_id','amount');
        }
        //$carts是一个数组
        //获取购物车商品信息
        $goodsList=Goods::find()->where(['in','id',array_keys($carts)])->all();
        return $this->render('cart',['carts'=>$carts,'goodsList'=>$goodsList]);
    }

    //AJAX修改购物车商品数量
    public function actionAjaxCart($type)
    {
        //登录操作数据库 未登录操作cookie
        switch ($type) {
            case 'change':
                $goods_id = \Yii::$app->request->post('goods_id');
                $amount = \Yii::$app->request->post('amount');
                if (\Yii::$app->user->isGuest) {
                    $cookies = \Yii::$app->request->cookies;
                    $carts = $cookies->getValue('carts');
                    if ($carts) {
                        $carts = unserialize($carts);
                    } else {
                        $carts = [];
                    }
                    //修改购物车商品数量
                    $carts[$goods_id] = $amount;
                    //保存
                    $cookies = \Yii::$app->response->cookies;
                    $cookie = new Cookie();
                    $cookie->name = 'carts';
                    $cookie->value = serialize($carts);
                    $cookie->expire=time()+3600*7*24;
                    $cookies->add($cookie);

                } else {
                    //用户登录时修改数量
                     $member_id=\Yii::$app->user->id;
                     $cart=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);
                     $cart->amount=$amount;
                     $cart->save();
                }
                break;
            case 'del':
                $goods_id = \Yii::$app->request->post('goods_id');
                //未登录 删除cookie
                if(\Yii::$app->user->isGuest){
                    //读取cookie
                    $cookies=\Yii::$app->request->cookies;
                    $carts=$cookies->getValue('carts');
                    $carts=unserialize($carts);
                    //删除对应数据
                    unset($carts[$goods_id]);
                    //重新写入cookie;
                    $cookies = \Yii::$app->response->cookies;
                    $cookie = new Cookie();
                    $cookie->name = 'carts';
                    $cookie->value = serialize($carts);
                    $cookie->expire=time()+3600*7*24;
                    $cookies->add($cookie);
                    return "success";
                }else{
                    //登录 删除数据表中数据
                    $member_id=\Yii::$app->user->id;
                    $cart=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);
                    $cart->delete();
                    return "success";
                }
                break;
        }
    }

}