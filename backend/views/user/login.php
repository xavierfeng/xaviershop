<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput();
echo $form->field($model,'password')->passwordInput();
//记住我
echo $form->field($model,'remember')->inline()->checkbox(['1'=>'记住我']);
//验证码
echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),['template'=>'<div class="row"><div class="col-lg-2">{input}</div><div class="col-lg-2">{image}</div></div>']);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();