<?php

$form = \yii\bootstrap\ActiveForm::begin();
//菜单名称
echo $form->field($menu,'name')->textInput();
//上级菜单名称
echo $form->field($menu,'menu')->dropDownList([0=>'顶级菜单']+$menus, ['prompt' => '==选择上级菜单==']);
//路由
echo $form->field($menu,'route')->dropDownList($routes,['prompt' => '==选择路由==']);
//排序
echo $form->field($menu,'sort')->textInput();
//提交按钮
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();