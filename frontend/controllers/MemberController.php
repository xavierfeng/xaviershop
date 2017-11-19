<?php
namespace frontend\controllers;

use backend\models\ArticleCategory;
use backend\models\GoodsCategory;
use frontend\components\Sms;
use frontend\models\Cart;
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
                    //登录时判断cookie中是否有购物车信息
                    $cookies=\Yii::$app->request->cookies;
                    $carts=$cookies->getValue('carts');
                    if($carts){
                        //存在 将购物车信息保存到数据表中
                        //cookie中商品信息
                        $carts=unserialize($carts);
                        //判断是否存在登录用户已经加入购物车的商品
                        $member_id=\Yii::$app->user->id;
                        $tableCarts=Cart::find()->Where(['member_id'=>$member_id])->all();
                        if($tableCarts){
                            foreach ($tableCarts as $tableCart){
                                if(array_key_exists($tableCart->goods_id,$carts)) {
                                    //相同的进行累加
                                 $tableCart->amount += $carts[$tableCart->goods_id];
                                 $tableCart->save();
                                 //排除掉相同goods_id的键值对
                                 unset($carts[$tableCart->goods_id]);
                                }
                            }
                        }
                        if($carts!=[]){
                            //不相同的新增
                            foreach ($carts as $goods_id=>$amount){
                                $newCart= new Cart();
                                $newCart->goods_id=$goods_id;
                                $newCart->amount=$amount;
                                $newCart->member_id=$member_id;
                                $newCart->save();
                            }
                        }
                        //删除cookie
                        $cookies=\Yii::$app->response->cookies;
                        $cookies->remove('carts');
                    }
                    return $this->goBack();
                }
            }
        }
        \Yii::$app->user->setReturnUrl(\Yii::$app->request->referrer);
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
        $html=GoodsCategory::getGoodsCategorys();
        return $this->render('index',['html'=>$html,'article_categorys'=>$article_categorys]);
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