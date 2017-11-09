<?php
namespace backend\models;

use yii\base\Model;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permissions;

    public function rules()
    {
        return[
          [['name','description'],'required'],
            ['permissions','safe'],
        ];
    }

    //设置属性标签名称
    public function attributeLabels()
    {
        return [
            'name'=>'角色名称:',
            'description'=>'角色描述:',
            'permissions'=>'权限:',
        ];
    }
    //判断角色名是否重复
    public function check()
    {
        $auth= \Yii::$app->authManager;
        if($auth->getRole($this->name)){
            return false;
        }else{
            return true;
        }
    }
    //修改判断角色名是否重复
    public function checkName($name)
    {
        $auth= \Yii::$app->authManager;
        if($auth->getRole($this->name)&&($this->name!=$name)){
            return false;
        }else{
            return true;
        }
    }
}