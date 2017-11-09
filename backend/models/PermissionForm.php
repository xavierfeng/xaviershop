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
            'name'=>'权限(路由):',
            'description'=>'权限描述:',
        ];
    }

    //添加判断权限名是否重复
    public function check()
    {
        $auth= \Yii::$app->authManager;
        if($auth->getPermission($this->name)){
            return false;
        }else{
            return true;
        }
    }

    //修改判断权限名是否重复
    public function checkName($name)
    {
        $auth= \Yii::$app->authManager;
        if($auth->getPermission($this->name)&&($this->name!=$name)){
            return false;
        }else{
            return true;
        }
    }
}