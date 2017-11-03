<?php
$form = \yii\bootstrap\ActiveForm::begin();
//品牌名称
echo $form->field($brand,'name')->textInput();
//品牌简介
echo $form->field($brand,'intro')->textInput();
//LOGO
echo $form->field($brand,'imgFile')->fileInput();
//品牌状态
echo $form->field($brand,'status')->inline()->radioList(['0'=>'隐藏','1'=>'显示']);
//验证码
echo $form->field($brand,'code')->widget(\yii\captcha\Captcha::className(),['template'=>'<div class="row"><div class="col-lg-2">{input}</div><div class="col-lg-2">{image}</div></div>']);
//提交按钮
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();