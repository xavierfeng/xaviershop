<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '后台管理系统',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems =[];
    $menuItems1 = [
        ['label' => '商品管理',
            'items' =>[
                [
                    'label'=>'商品列表',
                    'url' => ['/goods/index'],
                ],
                [
                    'label'=>'商品添加',
                    'url' => ['/goods/add'],
                ],
                [
                    'label'=>'商品分类列表',
                    'url' => ['/goods-category/index'],
                ],
                [
                    'label'=>'商品分类添加',
                    'url' => ['/goods-category/add'],
                ],
            ]
        ],
        ['label' => '品牌管理',
            'items' =>[
                [
                    'label'=>'品牌列表',
                    'url' => ['/brand/index'],
                ],
                [
                    'label'=>'品牌添加',
                    'url' => ['/brand/add'],
                ],
            ]
        ],
        ['label' => '文章管理',
         'items' =>[
             [
              'label'=>'文章列表',
              'url' => ['/article/index'],
             ],
             [
                 'label'=>'文章分类列表',
                 'url' => ['/article-category/index'],
             ],
             [
                 'label'=>'文章添加',
                 'url' => ['/article/add'],
             ],
             [
                 'label'=>'文章分类添加',
                 'url' => ['/article-category/add'],
             ],
         ]
        ],
        ['label' => '用户管理',
            'items' =>[
                [
                    'label'=>'用户列表',
                    'url' => ['/user/index'],
                ],
                [
                    'label'=>'用户添加',
                    'url' => ['/user/add'],
                ],
            ]
        ],
        ['label' => 'Rbac管理',
            'items' =>[
                [
                    'label'=>'权限列表',
                    'url' => ['/auth/view-pms'],
                ],
                [
                    'label'=>'添加权限',
                    'url' => ['/auth/add-pms'],
                ],
                [
                    'label'=>'角色列表',
                    'url' => ['/auth/view-role'],
                ],
                [
                    'label'=>'添加角色',
                    'url' => ['/auth/add-role'],
                ],
            ]
        ],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '登录', 'url' => ['/user/login']];
    } else {
        $menuItems[] = [
            'label' => '欢迎! (' . Yii::$app->user->identity->username . ')',
            'items'=>[
                [
                    'label'=>'修改密码',
                    'url' => ['/user/password'],
                ],
                    [
                        'label'=>'注销',
                        'url' => ['/user/logout'],
                    ]
            ]

        ];
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' =>$menuItems1
        ]);
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' =>$menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; 后台管理系统 <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
