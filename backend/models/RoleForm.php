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
}