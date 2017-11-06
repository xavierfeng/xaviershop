<?php
$form = \yii\bootstrap\ActiveForm::begin();
//品牌名称
echo $form->field($brand,'name')->textInput();
//品牌简介
echo $form->field($brand,'intro')->textInput();
//LOGO
echo $form->field($brand,'logo')->hiddenInput();
/*
 * WebUploader
 * 注册css和js文件
 */
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',[
    'depends'=>\yii\web\JqueryAsset::className(),//指定依赖关系,webuploader.js必须在jquery后面加载(依赖于jquery)
//    'position'=>\yii\web\View::POS_END //指定加载文件的位置
]);
$url = \yii\helpers\Url::to(['upload']);
$this->registerJs(
    <<<JS
    // 初始化Web Uploader
    var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    swf:'/js/Uploader.swf',

    // 文件接收服务端。
    server: '{$url}',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',

    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif,'
    }
});
// 文件上传成功，回显图片
uploader.on( 'uploadSuccess', function( file,response) {
    //上传文件的路径response.url
    //将图片地址赋值给img标签
    $('#img').attr('src',response.url);
    //图片地址赋值给logo
    $('#brand-logo').val(response.url)
});
JS

)
?>
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
    <div><img id="img" /></div>
</div>

<?php
//品牌状态
echo $form->field($brand,'status')->inline()->radioList(['0'=>'隐藏','1'=>'显示']);
//验证码
echo $form->field($brand,'code')->widget(\yii\captcha\Captcha::className(),['template'=>'<div class="row"><div class="col-lg-2">{input}</div><div class="col-lg-2">{image}</div></div>']);
//提交按钮
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();