<?php
namespace frontend\controllers;

use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Goods;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\db\Exception;
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
                $order->delivery_id=$request->post('delivery');//送货方式id
                $order->delivery_name=Order::$deliveries[$order->delivery_id][0];//送货方式
                $order->delivery_price=Order::$deliveries[$order->delivery_id][1];//价格
                $order->payment_id=$request->post('pay');//支付方式id
                $order->payment_name=Order::$pays[$order->payment_id][0];//送货方式
                $order->status=1;//订单状态
                $order->total=$order->delivery_price;
                $order->create_time=time();

                //开启事务(操作数据表之前)
                $transaction=\Yii::$app->db->beginTransaction();
                try{
                    $order->save();//保存订单表
                    $order_id=$order->id;//订单id
                    $carts=Cart::find()->where(['member_id'=>$member_id])->all();//用户购物车
                    foreach($carts as $cart){
                        //判断商品库存是否足够
                        if($cart->amount>$cart->goods->stock){
                            throw new Exception($cart->goods->name.'商品库存不足');
                        }
                        $order_goods=new OrderGoods();
                        $order_goods->order_id=$order_id;//订单id
                        $order_goods->goods_id=$cart->goods_id;//商品id
                        $order_goods->goods_name=$cart->goods->name;//商品名称
                        $order_goods->logo=$cart->goods->logo;//商品logo
                        $order_goods->price=$cart->goods->shop_price;//价格
                        $order_goods->amount=$cart->amount;//商品数量
                        $order_goods->total=($cart->amount*$cart->goods->shop_price);//小计
                        $order_goods->save();//保存订单商品表
                        $order->total+=$order_goods->total;//订单金额累加
                        Goods::updateAllCounters(['stock'=>-$cart->amount],['id'=>$cart->goods_id]);//扣减商品库存
                        $cart->delete();//保存订单表完成 删除购物车数据
                    }
                    $order->save();//再次保存order的总价
                    //提交事务
                    $transaction->commit();
                }catch (Exception $e){
                    //回滚
                    $transaction->rollBack();
                    //下单失败,跳转回购物车 并且提示商品库存不足
                    echo $e->getMessage();exit;
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
        return $this->render('orderlist',['html'=>$html,'orderList'=>$orderList]);
    }
}