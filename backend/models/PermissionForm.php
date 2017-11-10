<?php
namespace backend\models;

use yii\base\Model;

class PermissionForm extends Model{
    public $name;
    public $description;
    public $oldName;

    //场景 必须对应验证规则
    const SCENARIO_ADD ='add';
    const SCENARIO_EDIT ='edit';

    public function rules()
    {
        return[ //如果验证规则没有定义场景,则所有场景生效
            [['name','description'],'required'],
            //自定义的验证规则  on表示只在该场景下生效
            ['name','validateAddName','on'=>self::SCENARIO_ADD],
            ['name','validateEditName','on'=>self::SCENARIO_EDIT],
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

    public function validateAddName()
    {
        //自定义验证方法 只处理验证失败的情况
        $auth= \Yii::$app->authManager;
        if($auth->getPermission($this->name)){
            $this->addError('name','权限已存在!');
        }
    }

    public function validateEditName()
    {
        //自定义验证方法 只处理验证失败的情况 名称被修改,新名称已存在
        $auth= \Yii::$app->authManager;
        if($auth->getPermission($this->name)&&($this->name!=$this->oldName)){
            $this->addError('name','权限已存在!');
        }
    }
    //添加权限
    public function add()
    {
        $auth= \Yii::$app->authManager;
        $permission = $auth->createPermission($this->name);
        $permission->description = $this->description;
        $auth->add($permission);
        return  true;

    }

    //修改权限
    public function edit($name)
    {
        $auth= \Yii::$app->authManager;
        $newPermission =$auth->createPermission($this->name);
        $newPermission->description=$this->description;
        $auth->update($name,$newPermission);
        return true;
    }
}