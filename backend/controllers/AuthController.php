<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;

class AuthController extends \yii\web\Controller
{
    //添加权限
    public function actionAddPms(){
        $auth= \Yii::$app->authManager;
        $model= new PermissionForm();
        $request =\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->check()){
                    $permission = $auth->createPermission($model->name);
                    $permission->description = $model->description;
                    $auth->add($permission);
                    //提示 跳转
                    \Yii::$app->session->setFlash('success', '添加权限成功');
                    return $this->redirect(['auth/view-pms']);
                }else{
                    $model->addError('name','权限已存在!');
                }
            }
        }
        return $this->render('add-pms',['model'=>$model]);
    }
    //修改权限
    public function actionUpdatePms($pmsName){
        $auth= \Yii::$app->authManager;
        $model= new PermissionForm();
        $request =\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->checkName($pmsName)){
                    $newPermission =$auth->createPermission($model->name);
                    $newPermission->description=$model->description;
                    $auth->update($pmsName,$newPermission);
                    //提示 跳转
                    \Yii::$app->session->setFlash('success', '修改权限成功');
                    return $this->redirect(['auth/view-pms']);
                }else{
                    $model->addError('name','权限名已存在!');
                }

            }
        }
        $permission = $auth->getPermission($pmsName);
        $model->name=$permission->name;
        $model->description=$permission->description;
        return $this->render('add-pms',['model'=>$model]);
    }
    //权限列表
    public function actionViewPms(){
        $auth= \Yii::$app->authManager;
        $permissions=$auth->getPermissions();// 权限列表
        return $this->render('view-pms',['permissions'=>$permissions]);
    }

    //权限删除
    public function actionDelPms($pmsName){
        $auth= \Yii::$app->authManager;
        $permission = $auth->getPermission($pmsName);
        $auth->remove($permission);// 删除权限
        //提示 跳转
        \Yii::$app->session->setFlash('success', '删除权限成功');
        return $this->redirect(['auth/view-pms']);
    }
    //添加角色
    public function actionAddRole(){
        $auth = \Yii::$app->authManager;
        $model = new RoleForm();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                //判断角色名是否重复
                if($model->check()){
                    //创建角色
                    $role=$auth->createRole($model->name);
                    $role->description=$model->description;
                    $auth->add($role);//添加到数据表
                    foreach ($model->permissions as $permissionName){
                        //根据权限名称获取权限对象
                        $permission=$auth->getPermission($permissionName);
                        //给角色分配权限
                        $auth->addChild($role,$permission);
                    }
                    //提示 跳转
                    \Yii::$app->session->setFlash('success', '创建角色成功');
                    return $this->redirect(['auth/view-role']);
                }else{
                    $model->addError('name','该角色已存在');
                }

            }
        }

        $permissions=$auth->getPermissions();
        $permissions=ArrayHelper::map($permissions,'name','description');
        return $this->render('add-role',['model'=>$model,'permissions'=>$permissions]);
    }

    //修改角色
    public function actionUpdateRole($roleName){
        $auth = \Yii::$app->authManager;
        $model = new RoleForm();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->checkName($roleName)){
                    //创建一个新的角色
                    $newRole=$auth->createRole($model->name);
                    $newRole->description=$model->description;
                    $auth->update($roleName,$newRole);
                    $auth->removeChildren($newRole);
                    if($model->permissions){
                        foreach ($model->permissions as $permissionName){
                            //根据权限名称获取权限对象
                            $permission=$auth->getPermission($permissionName);
                            //给角色分配权限
                            $auth->addChild($newRole,$permission);
                        }
                    }
                    //提示 跳转
                    \Yii::$app->session->setFlash('success', '修改角色成功');
                    return $this->redirect(['auth/view-role']);
                }else{
                    $model->addError('name','该角色名已存在');
                }
            }
        }
        //获取该角色信息
        $role=$auth->getRole($roleName);
        $model->name=$role->name;
        $model->description=$role->description;
        //角色已有权限
        $permission=ArrayHelper::map($auth->getPermissionsByRole($roleName),'name','name');
        $model->permissions=$permission;
        //所有权限
        $permissions=$auth->getPermissions();
        $permissions=ArrayHelper::map($permissions,'name','description');
        return $this->render('add-role',['model'=>$model,'permissions'=>$permissions]);
    }

    //角色列表
    public function actionViewRole(){
        $auth = \Yii::$app->authManager;
        $roles=$auth->getRoles();
        return $this->render('view-role',['roles'=>$roles]);
    }

    //角色删除
    public function actionDelRole($roleName){
        $auth= \Yii::$app->authManager;
        //获取角色
        $role=$auth->getRole($roleName);
        //删除角色
        $auth->remove($role);
        //提示 跳转
        \Yii::$app->session->setFlash('success', '删除角色成功');
        return $this->redirect(['auth/view-role']);
    }

    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }
}