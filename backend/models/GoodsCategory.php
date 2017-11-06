<?php
namespace backend\models;

use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;

class GoodsCategory extends ActiveRecord
{
    public $code;

    //获取zTree需要的数据
    public static function getZtreeNodes()
    {
        return self::find()->select(['id','name','parent_id'])->asArray()->all();

    }
    //设置属性标签名称
    public function attributeLabels()
    {
        return [
            'name'=>'分类名称',
            'intro'=>'分类简介',
            'parent_id'=>'上级分类',
            'code'=>'验证码',
        ];
    }
    //设置验证规则
    public function rules()
    {
        return [
            ['name','required'],
            ['parent_id','required'],
            ['intro','required'],
            ['code','captcha'],
        ];
    }
    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',//必须打开
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }
}