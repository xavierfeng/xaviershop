<?php
namespace frontend\controllers;

use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Goods;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\web\Controller;
use yii\web\Request;

class OrderController extends Controller
{
    public $enableCsrfValidation =false;

    //确认订单页
    public function actionOrder()
    {
        if(\Yii::$app->user->isGuest){
            //用户未登录 跳转到登录页面
            return $this->redirect(['member/login']);
        }
        //收货地址
        $address=Address::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        //当前登录用户的购物车商品
        $carts=Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        if(!$carts){
            return $this->redirect(['goods/cart']);
        }
        return $this->render('order',['address'=>$address,'carts'=>$carts]);
    }

    //生成订单
    public function actionProduceOrder()
    {
            $order = new Order();
            $request=new Request();
            $member_id=\Yii::$app->user->id;
            if($request->isPost){
                $address_id=$request->post('address_id');
                $address=Address::findOne(['id'=>$address_id]);
                $order->member_id=$member_id;//登录用户id
                $order->name=$address->member;//收货人
                $order->province=$address->province;//省
                $order->city=$address->city;//市
                $order->area=$address->county;//县
                $order->tel=$address->tel;//电话
                $order->address=$address->address;//详细地址
                $order->delivery_name=$request->post('delivery');//送货方式
                switch ($order->delivery_name){
                    case "普通快递送货上门":
                        $order->delivery_price=floatval(10);//送货价格
                        break;
                    case "特快专递":
                        $order->delivery_price=floatval(40);//送货价格
                        break;
                    case "加急快递送货上门":
                    $order->delivery_price=floatval(40);//送货价格
                        break;
                    case "平邮":
                    $order->delivery_price=floatval(10);//送货价格
                        break;
                }
                $order->payment_name=$request->post('pay');//支付方式
                $carts=Cart::find()->where(['member_id'=>$member_id])->all();//用户购物车
                $order->total="";
                foreach ($carts as $cart){
                    $order->total+=($cart->goods->shop_price*$cart->amount);
                }
                $order->total+=$order->delivery_price;
                $order->status=1;
                $order->create_time=time();
                if($order->validate()){
                    $order->save();//保存订单表
                }
                $order_id=$order->id;//订单id
                foreach($carts as $cart){
                    $order_goods=new OrderGoods();
                    $order_goods->order_id=$order_id;//订单id
                    $order_goods->goods_id=$cart->goods_id;//商品id
                    $order_goods->goods_name=$cart->goods->name;//商品名称
                    $order_goods->logo=$cart->goods->logo;//商品logo
                    $order_goods->price=$cart->goods->shop_price;//价格
                    $order_goods->amount=$cart->amount;//商品数量
                    $order_goods->total=($cart->amount*$cart->goods->shop_price);//小计
                    if($order_goods->validate()){
                        $order_goods->save();//保存订单商品表
                        $cart->delete();//保存订单表完成 删除购物车数据
                    }
                }
                return $this->redirect(['order/orderfinish']);
            }else{
                return $this->redirect(['member/index']);
            }
    }

    //订单完成页面
    public function actionOrderfinish()
    {
        if(\Yii::$app->user->isGuest){
            //用户未登录 跳转到登录页面
            return $this->redirect(['member/login']);
        }
        return $this->render('orderfinish');
    }

    //订单列表页
    public function actionOrderlist()
    {
        //商品分类
        $html=GoodsCategory::getGoodsCategorys();
        //订单
        $member_id=\Yii::$app->user->id;
        //获取所有登录用户的订单
        $orderList=Order::find()->where(['member_id'=>$member_id])->all();
        //登录用户的订单id
        $orderIds=[];
        foreach ($orderList as $order){
            $orderIds[]=$order->id;
        }
        //登录用户所有订单商品信息
        $orderGoodsList=OrderGoods::find()->where(['in','order_id',$orderIds])->all();
        return $this->render('orderlist',['html'=>$html,'orderList'=>$orderList,'orderGoodsList'=>$orderGoodsList]);
    }
}