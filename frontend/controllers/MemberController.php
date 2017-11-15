<?php
namespace frontend\controllers;

use backend\models\ArticleCategory;
use backend\models\GoodsCategory;
use frontend\components\Sms;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\web\Controller;

class MemberController extends Controller
{
    public  $enableCsrfValidation = false;
    //登录
    public function actionLogin()
    {
        $model= new LoginForm();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post(),'');
            if($model->validate()) {
                if($model->login()){
                    //跳转首页
                    $this->redirect(['member/index']);
                }
            }
        }
        return $this->render('login');
    }

    //注销
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        return $this->redirect(['login']);
    }

    //注册
    public function actionRegist()
    {
        $model=new Member();
        $request=\Yii::$app->request;
        if($request->isPost){
            $model->load($request->post(),'');
            if($model->validate()) {
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
                $model->status=1;
                $model->created_at=time();
                $model->save(false);
                //跳转首页
                $this->redirect(['member/login']);
            }
        }
        return $this->render('regist');
    }

    //验证用户名重复
    public function actionCheckUsername($username)
    {
        if(Member::findOne(['username'=>$username])){
            return "false";
        }
        return "true";
    }
    //验证邮箱重复
    public function actionCheckEmail($email)
    {
        if(Member::findOne(['email'=>$email])){
            return "false";
        }
        return "true";
    }
    //验证手机重复
    public function actionCheckTel($tel)
    {
        if(Member::findOne(['tel'=>$tel])){
            return "false";
        }
        return "true";
    }

    //首页
    public function actionIndex()
    {
        $article_categorys= ArticleCategory::find()->all();
        $goods_categorys1=GoodsCategory::find()->where(['parent_id'=>0])->all();
        return $this->render('index',['goods_categorys1'=>$goods_categorys1,'article_categorys'=>$article_categorys]);
    }

    //接受手机号码发送短信
    public function actionAjaxSms($phone)
    {
        $code=rand(1000,9999);
        //阿里大于代码
        $response = Sms::sendSms(
            "Xavier故事", // 短信签名
            "SMS_109390470", // 短信模板编号
            $phone, // 短信接收者
            Array(  // 短信模板中字段的值
                "code"=>$code
            //,
               // "product"=>"dsd"
            )//,
            //"123"   // 流水号,选填
        );
        //根据$response结果判断是否发送成功
        //$response->Code是否是OK
        if($response->Code=="OK"){
            //保存验证码到redis
            $redis = new \Redis();
            $redis->connect('127.0.0.1');
            $redis->set('msg_'.$phone,$code,10*60);

            //发送成功
            return "true";
        }else{
        //发送失败
        return "false";
        }
    }

    //验证验证码
    public function actionCheckSms()
    {
        $tel=$_POST['tel'];
        $tel_captcha=$_POST['tel_captcha'];
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        if($redis->get('msg_'.$tel)&&($tel_captcha==$redis->get('msg_'.$tel))){
            return "true";
        }else{
            //验证失败
            return "false";
        }

    }

}