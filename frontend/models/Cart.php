<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Cart extends ActiveRecord
{
    public function rules()
    {
        return[
            ['goods_id','required'],
            ['member_id','required'],
            ['amount','required'],
        ];
    }

    //根据商品id获取商品信息
    public function getGoods()
    {
        return $this->hasOne(\backend\models\Goods::className(),['id'=>'goods_id']);
    }
}