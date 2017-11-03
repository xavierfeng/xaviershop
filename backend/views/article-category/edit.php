<?php
$form = \yii\bootstrap\ActiveForm::begin();
//文章分类
echo $form->field($articleCate,'name')->textInput();
//分类简介
echo $form->field($articleCate,'intro')->textInput();
//分类状态
echo $form->field($articleCate,'status')->inline()->radioList(['0'=>'隐藏','1'=>'显示']);
//验证码
echo $form->field($articleCate,'code')->widget(\yii\captcha\Captcha::className(),['template'=>'<div class="row"><div class="col-lg-2">{input}</div><div class="col-lg-2">{image}</div></div>']);
//提交按钮
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();