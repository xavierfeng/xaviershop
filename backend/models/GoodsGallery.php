<?php
namespace backend\models;

use yii\db\ActiveRecord;

class GoodsGallery extends ActiveRecord
{
    //设置验证规则
    public function rules()
    {
        return [
            ['goods_id','safe'],
            ['path','required'],
        ];
    }

    //设置属性标签名称
    public function attributeLabels()
    {
        return [
            'goods_id'=>'上传图片:',
            'path'=>'上传',

        ];
    }
}