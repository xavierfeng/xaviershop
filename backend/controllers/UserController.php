<?php
/////////////user文章////////////////
namespace backend\controllers;

use backend\models\LoginForm;
use backend\models\PasswordForm;
use backend\models\User;
use yii\data\Pagination;
use yii\rbac\DbManager;
use yii\web\Controller;
use yii\web\Request;

class UserController extends Controller
{
    public $enableCsrfValidation = false;

    //列表功能
    public function actionIndex()
    {
        //分页工具类
        $query= User::find()->where('status>=0');
        $pager = new Pagination();
        $pager->pageSize = 3;
        $pager->totalCount=$query->count();
        $users=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['users'=>$users,'pager'=>$pager]);
    }

    //添加用户
    public function actionAdd()
    {
        $request = new Request();
        $user = new User();
        $auth = new DbManager();
        if($request->getIsPost()){
            //接受表单数据
            $user->load($request->post());
            //验证表单数据
            if($user->validate()){
                //验证通过
                //保存用户表信息
                $user->status = 10;
                $user->created_at=time();
                //随机生成auth_key
                $user->auth_key=\Yii::$app->security->generateRandomString();
                //密码加密保存
                $user->password_hash= \Yii::$app->security->generatePasswordHash($user->password_hash);
                $user->save();
                //如果添加了角色 对用户角色进行添加
                if($user->roles){
                    //获取此次添加的id
                    $id=$user->id;
                    $roles = $user->roles;
                    foreach ($roles as $rolename) {
                        $role = $auth->getRole($rolename);
                        $auth->assign($role, $id);
                    }
                }
                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect(['user/index']);
            }else{
                //验证未通过
                //获取错误信息
                $error=$user->getErrors();
                //显示错误信息
                //跳转
                \Yii::$app->session->setFlash('danger',$error['username'][0]);
                $this->redirect(['user/add']);
            }
        }else{
            $roles = $auth->getRoles();
            $array=[];
            foreach ($roles as $role){
                $array[$role->name] = $role->name;
            }
            //显示添加页面
            return $this->render('add',['user'=>$user,'array'=>$array]);
        }
    }

    //修改用户信息
    public function actionUpdate($id)
    {
        $request = new Request();
        //数据库查询对应id数据
        $user = User::findOne(['id' => $id]);
        if($request->getIsPost()){
            //接受表单数据
            $user->load($request->post());
            //验证表单数据
            if($user->validate()){
                $user->updated_at = time();
                $user->save(false);
                //跳转
                \Yii::$app->session->setFlash('success','修改成功');
                $this->redirect(['user/index']);
            }else{
                //验证未通过
                //获取错误信息
                $error=$user->getErrors();
                //显示错误信息
                var_dump($error);
            }
        }else{

            return $this->render('edit',['user'=>$user]);
        }
    }


    //删除用户
    public function actionDelete()
    {
        $id =\Yii::$app->request->post('id');
        $user=user::findOne(['id'=>$id]);
        if($user){
            $user->delete();
            echo 'success';
        }
    }

    //登录
    public function actionLogin(){
        //显示登录表单
        $model = new LoginForm();
        $request = \Yii::$app->request;
        if($request->isPost){
            //表单提交,接收表单数据
            $model->load($request->post());
            if($model->validate()){
                //验证账号密码是否正确
                if($model->login()){
                    //提示信息  跳转
                    \Yii::$app->session->setFlash('success','登录成功');
                    //跳转
                    return $this->redirect(['index']);
                }
            }
        }
        //显示登录页面
        return $this->render('login',['model'=>$model]);
    }

    //注销
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['login']);
    }

    //修改密码
    public function actionPassword()
    {
        $model = new PasswordForm();
        //接收表单数据,验证旧密码
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //验证旧密码 新密码和确认新密码一致
                $password_hash = \Yii::$app->user->identity->password_hash;
                if (\Yii::$app->security->validatePassword($model->oldPassword, $password_hash)) {
                    //旧密码正确 更新当前用户的密码
                    User::updateAll([
                        'password_hash'=>\Yii::$app->security->generatePasswordHash($model->newPassword),'auth_key'=>\Yii::$app->security->generateRandomString()
                    ],//修改密码时重新设置auth_key让自动登录失效
                        ['id'=>\Yii::$app->user->id]
                    );
                    \Yii::$app->getSession()->setFlash('success','密码修改成功,请重新登录');
                    return $this->redirect(['logout']);

                } else {
                    //旧密码不正确
                    $model->addError('oldPassword', '旧密码不正确');
                }

            }
        }
        return $this->render('password',['model'=>$model]);
    }
}