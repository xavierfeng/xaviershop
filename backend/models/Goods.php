<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Goods extends ActiveRecord
{
    public $code;

    //设置属性标签名称
    public function attributeLabels()
    {
        return [
            'name'=>'商品名称',
            'logo'=>'LOGO图片',
            'goods_category_id'=>'商品分类',
            'brand_id'=>'商品分类',
            'market_price'=>'市场价格',
            'shop_price'=>'商品价格',
            'stock'=>'库存',
            'is_on_sale'=>'是否在售',
            'code'=>'验证码'
        ];
    }
    //设置验证规则
    public function rules()
    {
        return [
            ['id','safe'],
            ['name','required'],
            ['logo','required'],
            ['goods_category_id','required'],
            ['brand_id','required'],
            ['market_price','required'],
            ['shop_price','required'],
            ['stock','required'],
            ['is_on_sale','required'],
            ['code','captcha'],
        ];
    }
    //找分类名称
    public function getCategory()
    {
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }
    //找商品相册图片
    public function getGallery()
    {
        return $this->hasMany(GoodsGallery::className(),['goods_id'=>'id']);
    }
}