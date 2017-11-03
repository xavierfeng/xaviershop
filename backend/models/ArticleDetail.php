<?php
namespace backend\models;

use yii\db\ActiveRecord;

class ArticleDetail extends ActiveRecord
{


    //设置属性标签名称
    public function attributeLabels()
    {
        return [
            'content'=>'文章内容',
        ];
    }
    //设置验证规则
    public function rules()
    {
        return [
            ['article_id','safe'],
            ['content','required'],
        ];
    }
}