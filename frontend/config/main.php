<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    //设置语言
    'language'=>'zh-CN',
    //设置布局文件(前台不需要yii2自带的布局)
    'layout'=>false,
    //设置默认路由
    'defaultRoute'=>'member/index',
    //修改时区
    'timeZone'=>'PRC',
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'class'=>'yii\web\User',
            //指定实现认证接口的类
            'identityClass' => 'frontend\models\Member',
            //'loginUrl'=>'login',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'on beforeLogin'=>function($event){
                $model= $event->identity;
                $model->last_login_time=time();
                $model->last_login_ip=Yii::$app->request->getUserIP();
                $model->save(false);
            }
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],

    ],
    'params' => $params,
];
