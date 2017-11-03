<?php
$form = \yii\bootstrap\ActiveForm::begin();
//文章名称
echo $form->field($article,'name')->textInput();
//文章简介
echo $form->field($article,'intro')->textInput();
//文章分类
echo $form->field($article,'article_category_id')->dropDownList($category);
//文章状态
echo $form->field($article,'status')->inline()->radioList(['0'=>'隐藏','1'=>'显示']);
//文章内容
echo $form->field($articleDetail,'content')->textarea(['rows'=>6]);
//验证码
echo $form->field($article,'code')->widget(\yii\captcha\Captcha::className(),['template'=>'<div class="row"><div class="col-lg-2">{input}</div><div class="col-lg-2">{image}</div></div>']);
//提交按钮
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();