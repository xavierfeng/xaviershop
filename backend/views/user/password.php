<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'oldPassword')->passwordInput();
echo $form->field($model,'newPassword')->passwordInput();
echo $form->field($model,'rePassword')->passwordInput();
//验证码
echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),['template'=>'<div class="row"><div class="col-lg-2">{input}</div><div class="col-lg-2">{image}</div></div>']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();