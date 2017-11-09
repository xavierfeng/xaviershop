<?php
namespace backend\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Menu extends ActiveRecord
{

    //设置属性标签名称
    public function attributeLabels()
    {
        return[
            'name'=>'菜单名称',
            'menu'=>'上级菜单',
            'route'=>'地址/路由',
            'sort'=>'排序',
        ];
    }

    //设置验证规则
    public function rules()
    {
        return[
            ['id','safe'],
            ['name','required'],
            ['menu','required'],
            ['route','safe'],
            ['sort','required'],

        ];
    }

    //获取所有菜单上级分类及名称
    public function getMenus()
    {
       return  ArrayHelper::map(self::find()->asArray()->all(),'id','name');
    }
}