<?php
namespace backend\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class ArticleCategory extends ActiveRecord
{
    public $code;

    //设置属性标签名称
    public function attributeLabels()
    {
        return [
            'name'=>'分类名称',
            'intro'=>'分类简介',
            'status'=>'分类状态',
            'code'=>'验证码',
        ];
    }
    //设置验证规则
    public function rules()
    {
        return [
            ['id','safe'],
            ['name','required'],
            ['intro','required'],
            ['status','required'],
            ['code','captcha'],
        ];
    }

    //获取所有文章分类
    public function getArticleCategory()
    {
        return ArrayHelper::map(self::find()->asArray()->all(), 'id', 'name');
    }

    public function getChildren()
    {
        return $this->hasMany(Article::className(),['article_category_id'=>'id']);
    }
}