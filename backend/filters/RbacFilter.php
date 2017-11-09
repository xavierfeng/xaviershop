<?php
namespace backend\filters;

use yii\base\ActionFilter;
use yii\web\HttpException;

class RbacFilter extends ActionFilter{
    //拦截
    public function beforeAction($action)
    {
        //当前路由 $action->uniqueId
        //return \Yii::$app->user->can($action->uniqueId);

        if(!\Yii::$app->user->can($action->uniqueId)){
            //如果没有登录则跳转到登录页面
            if(\Yii::$app->user->isGuest){
                //send()确保立刻跳转
                return $action->controller->redirect(['user/login'])->send();
            }else{
                throw new HttpException(403,'对不起,您没有该操作权限');
            }
        };
        return parent::beforeAction($action);
    }

}