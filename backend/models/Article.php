<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Article extends ActiveRecord
{
    public $code;

    //设置属性标签名称
    public function attributeLabels()
    {
        return [
            'name'=>'文章名称',
            'intro'=>'文章简介',
            'article_category_id'=>'文章分类',
            'status'=>'文章状态',
            'code'=>'验证码',
        ];
    }
    //设置验证规则
    public function rules()
    {
        return [
            ['id','safe'],
            ['name','required'],
            ['article_category_id','required'],
            ['intro','required'],
            ['status','required'],
            ['code','captcha'],
        ];
    }

    //获取文章对应id的文章分类
    public function getArticlecategory()
    {
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }
}