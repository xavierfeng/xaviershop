<?php
namespace frontend\models;

use yii\base\Model;


class LoginForm extends Model{

    public $username;
    public $password_hash;
    public $remember;

    public function rules()
    {
        return[
        ['username','required'],
        ['password_hash','required'],
        ['remember','boolean'],
    ];

    }
    public function login()
    {
        //验证账号
        $member = Member::findOne(['username' => $this->username]);
        if ($member) {
            //验证密码
            //调用安全组件的验证密码方法来验证
            if (\Yii::$app->security->validatePassword($this->password_hash, $member->password_hash)) {
                //密码正确
                //将登录标识保存到session
                \Yii::$app->user->login($member, $this->remember ? 3600 : 0);

                return true;
            }
            return false;
        }
    }
}