<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Brand extends ActiveRecord
{
    public $code;

    //设置属性标签名称
    public function attributeLabels()
    {
        return [
            'name'=>'品牌名称',
            'intro'=>'品牌简介',
            'status'=>'品牌状态',
            'code'=>'验证码',
            'logo'=>'LOGO',
        ];
    }
    //设置验证规则
    public function rules()
    {
        return [
            ['id','safe'],
            ['name','required'],
            ['intro','required'],
            ['logo','required'],
            ['status','required'],
            ['code','captcha'],
        ];
    }
}