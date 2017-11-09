<?php
namespace backend\models;

use yii\base\Model;

class PermissionForm extends Model{
    public $name;
    public $description;

    public function rules()
    {
        return[
            [['name','description'],'required'],
        ];
    }

    //设置属性标签名称
    public function attributeLabels()
    {
        return [
            'name'=>'权限(路由admin/login):',
            'description'=>'权限描述:',
        ];
    }
}