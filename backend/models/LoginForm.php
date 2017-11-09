<?php
namespace backend\models;

use yii\base\Model;

class LoginForm extends Model{
    public $username;
    public $password;
    public $remember;
    public $code;

    public function rules()
    {
        return [
            [['username','password'],'required'],
            ['code','captcha'],
            ['remember','safe'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'code'=>'验证码',
            'remember'=>'记住我',
        ];
    }

    public function login($remember){
        //验证账号
        $user = User::findOne(['username'=>$this->username]);
        if($user){
            //验证密码
            //调用安全组件的验证密码方法来验证
            if(\Yii::$app->security->validatePassword($this->password,$user->password_hash)){
                //密码正确
                //判断用户是否被禁用
                if($user->status!=10){
                    \Yii::$app->session->setFlash('danger','该用户已被禁用,请联系管理员');return false;
                }
                $user->save(false);
                //将登录标识保存到session
                if($remember){
                    \Yii::$app->user->login($user,3600*24);
                }else{
                    \Yii::$app->user->login($user);
                }
                return true;
            }else{
                //添加错误信息
                $this->addError('password','账号或密码错误');
            }
        }else{
            $this->addError('username','账号或密码错误');
        }
        return false;
    }
}