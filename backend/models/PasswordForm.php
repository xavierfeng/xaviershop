<?php

namespace backend\models;

use yii\base\Model;

class PasswordForm extends Model
{
    //旧密码
    public $oldPassword;
    //新密码
    public $newPassword;
    //确认新密码
    public $rePassword;

    public $code;

    public function rules(){

        return[
            [['oldPassword','newPassword','rePassword'],'required'],
            //新密码和确认新密码一致
            ['rePassword','compare','compareAttribute'=>'newPassword','message'=>'两次密码不一致'],
            ['code','captcha']
              ];
    }

    public function attributeLabels()
    {
        return [
            'oldPassword'=>'旧密码',
            'newPassword'=>'新密码',
            'rePassword'=>'确认新密码',
            'code'=>'验证码',
        ];
    }
}