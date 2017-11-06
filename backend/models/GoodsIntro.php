<?php
namespace backend\models;

use yii\db\ActiveRecord;

class GoodsIntro extends ActiveRecord
{
    //设置属性标签名称
    public function attributeLabels()
    {
        return [
            'content'=>'商品描述',
        ];
    }
    //设置验证规则
    public function rules()
    {
        return [
            ['content','required'],
        ];
    }

}