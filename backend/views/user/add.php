<?php
$form = \yii\bootstrap\ActiveForm::begin();
//用户名
echo $form->field($user,'username')->textInput();
//用户密码
echo $form->field($user,'password_hash')->passwordInput();
//邮箱
echo $form->field($user,'email')->textInput();
//角色
echo $form->field($user,'roles')->inline()->checkboxList($array);
//提交按钮
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();