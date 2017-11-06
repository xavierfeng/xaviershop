<?php
namespace backend\models;

use yii\db\ActiveRecord;

class GoodsDayCount extends ActiveRecord
{
    //设置验证规则
    public function rules()
    {
        return [
            ['day','required'],
            ['count','required'],
        ];
    }

}