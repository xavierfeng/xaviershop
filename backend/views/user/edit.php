<?php
$form = \yii\bootstrap\ActiveForm::begin();
//用户名
echo $form->field($user,'username')->textInput();
//邮箱
echo $form->field($user,'email')->textInput();
//状态
echo $form->field($user,'status')->inline()->radioList(['0'=>'禁用','10'=>'启用']);
//角色
echo $form->field($user,'roles')->inline()->checkboxList($roles);
//提交按钮
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();