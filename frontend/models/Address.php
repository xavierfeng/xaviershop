<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Address extends ActiveRecord
{
    public function rules()
    {
        return[
            [['member','province','city','county','address','tel'],'required'],
            ['status','boolean'],
            ['id','safe'],
        ];
    }
}