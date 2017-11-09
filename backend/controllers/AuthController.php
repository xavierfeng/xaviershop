<?php

namespace backend\controllers;

use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;

class AuthController extends \yii\web\Controller
{
    //添加权限
    public function actionAddPms(){
        //$auth= \Yii::$app->authManager;
        $auth = new DbManager();
        $model= new PermissionForm();
        $request =\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $permission = $auth->createPermission($model->name);
                $permission->description = $model->description;
                $auth->add($permission);
                //提示 跳转
                \Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['auth/view-pms']);
            }
        }
        return $this->render('add-pms',['model'=>$model]);
    }
    //权限列表
    public function actionViewPms(){
        $auth = new DbManager();
        //$auth= \Yii::$app->authManager;
        $permissions=$auth->getPermissions();// 权限列表
        return $this->render('view-pms',['permissions'=>$permissions]);
    }

    //权限删除
    public function actionDelPms($id){
        $auth = new DbManager();
        //$auth= \Yii::$app->authManager;
        $permission = $auth->getPermission($id);
        $auth->remove($permission);// 删除权限
        //提示 跳转
        \Yii::$app->session->setFlash('success', '删除成功');
        return $this->redirect(['auth/view-pms']);
    }
    //添加角色
    public function actionAddRole(){
        $auth = new DbManager();
        //$auth = \Yii::$app->authManager;
        $model = new RoleForm();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
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
                \Yii::$app->session->setFlash('success', '创建成功');
                return $this->redirect(['auth/view-role']);
            }
        }

        $permissions=$auth->getPermissions();
        $permissions=ArrayHelper::map($permissions,'name','description');
        return $this->render('add-role',['model'=>$model,'permissions'=>$permissions]);
    }

    //角色列表
    public function actionViewRole(){
        $auth = new DbManager();
        //$auth = \Yii::$app->authManager;
        $roles=$auth->getRoles();
        return $this->render('view-role',['roles'=>$roles]);
    }

    //角色删除
    public function actionDelRole($id){
        $auth = new DbManager();
        //$auth= \Yii::$app->authManager;

        //获取角色
        $role=$auth->getRole($id);
        //删除角色
        $auth->remove($role);
        //提示 跳转
        \Yii::$app->session->setFlash('success', '删除成功');
        return $this->redirect(['auth/view-role']);
    }

    /*    public function behaviors()
        {
            return [
              'rbac'=>[
                  'class'=>RbacFilter::className(),
              ]
            ];
        }*/
}