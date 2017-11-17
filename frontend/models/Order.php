<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
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
            ['delivery_name','required'],
            ['delivery_price','required'],
            ['payment_name','required'],
            ['total','required'],
            ['status','required'],
            ['create_time','required'],
        ];
    }

}