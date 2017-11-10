<?php
namespace backend\models;

use yii\base\Model;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permissions;
    public $oldName;

    //场景 必须对应验证规则
    const SCENARIO_ADD ='add';
    const SCENARIO_EDIT ='edit';

    public function rules()
    {
        return[
          [['name','description'],'required'],
            ['permissions','safe'],
            //自定义的验证规则  on表示只在该场景下生效
            ['name','validateAddName','on'=>self::SCENARIO_ADD],
            ['name','validateEditName','on'=>self::SCENARIO_EDIT],
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

    //判断添加时角色名是否重复
    public function validateAddName()
    {
        //自定义验证方法 只处理验证失败的情况
        $auth= \Yii::$app->authManager;
        if($auth->getRole($this->name)){
            $this->addError('name','角色已存在!');
        }
    }

    //判断修改时角色名是否重复
    public function validateEditName()
    {
        //自定义验证方法 只处理验证失败的情况
        $auth= \Yii::$app->authManager;
        if($auth->getRole($this->name)&&($this->name!=$this->oldName)){
            $this->addError('name','角色已存在!');
        }
    }

    //添加角色
    public function add()
    {
        $auth= \Yii::$app->authManager;
        //创建角色
        $role=$auth->createRole($this->name);
        $role->description=$this->description;
        $auth->add($role);//添加到数据表
        foreach ($this->permissions as $permissionName){
            //根据权限名称获取权限对象
            $permission=$auth->getPermission($permissionName);
            //给角色分配权限
            $auth->addChild($role,$permission);
            return true;
        }
    }

    //修改角色
    public function edit($name)
    {
        $auth= \Yii::$app->authManager;
        //创建一个新的角色
        $newRole=$auth->createRole($this->name);
        $newRole->description=$this->description;
        $auth->update($name,$newRole);
        $auth->removeChildren($newRole);
        if($this->permissions){
            foreach ($this->permissions as $permissionName){
                //根据权限名称获取权限对象
                $permission=$auth->getPermission($permissionName);
                //给角色分配权限
                $auth->addChild($newRole,$permission);
            }
        }
        return true;
    }
}