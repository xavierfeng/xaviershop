<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
    //自定义配送方式
    public static $deliveries=[
        1=>["普通快递送货上门",15,"每张订单不满150.00元,运费15.00元"],
        2=>["顺丰快递",25,"每张订单不满200.00元,运费25.00元"],
        3=>["圆通快递",12,"每张订单不满120.00元,运费12.00元"],
        4=>["平邮",10,"每张订单不满100.00元,运费10.00元"],
    ];

    //自定义支付方式
    public static $pays=[
        1=>["在线支付","即时到帐，支持绝大数银行借记卡及部分银行信用卡"],
        2=>["货到付款","送货上门后再收款，支持现金、POS机刷卡、支票支付"],
        3=>["上门自提","自提时付款，支持现金、POS刷卡、支票支付"],
        4=>["邮局汇款","通过快钱平台收款 汇款后1-3个工作日到账"],
    ];


    public function rules()
    {
        return[
            ['member_id','required'],
            ['name','required'],
            ['province','required'],
            ['city','required'],
            ['area','required'],
            ['address','required'],
            ['tel','required'],
            ['delivery_id','required'],
            ['delivery_name','required'],
            ['delivery_price','required'],
            ['payment_id','required'],
            ['payment_name','required'],
            ['total','required'],
            ['status','required'],
            ['create_time','required'],
        ];
    }

    public function getOrdergoods()
    {
        return $this->hasMany(OrderGoods::className(),['order_id'=>'id']);
    }
}