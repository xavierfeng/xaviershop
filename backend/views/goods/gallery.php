<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>后台管理</title>
</head>
<body>
<table class="table table-bordered">
    <tr class="th">
        <td>图片ID</td>
        <td>内容</td>
        <td>操作</td>
    </tr>
    <tbody id="tbody">
    <?php foreach ($goodsGallerys as $goodsGallery):?>
        <tr>
            <td><?=$goodsGallery->goods_id?></td>
            <td><?=\yii\bootstrap\Html::img($goodsGallery->path)?></td>
            <td>
                <a href="javascript:;" class="btn btn-danger" id="<?=$goodsGallery->id?>">删除</a>
            </td>
        </tr>
    <?php endforeach;?>
</tbody>
<?php
/*
 * WebUploader
 * 注册css和js文件
 */
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',[
    'depends'=>\yii\web\JqueryAsset::className(),//指定依赖关系,webuploader.js必须在jquery后面加载(依赖于jquery)
//    'position'=>\yii\web\View::POS_END //指定加载文件的位置
]);
$url = \yii\helpers\Url::to(['upload-gallery','id'=>$goods_id]);
$urlDel =\yii\helpers\Url::to(['goods/gallery-delete']);
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
       $("<tr><td>"+response.goodsId+"</td><td><img src="+response.url+"></td><td> <a href='javascript:;' class='btn btn-danger' id="+response.id+">删除</a></td></tr>").appendTo("#tbody")
});

//删除图片
  $("table").on("click",".btn-danger",function(){
            if(confirm('是否确认删除')){
                var that=this;
                 var url = "{$urlDel}";
            $.post(url,{"id":that.id},function(data){
                if(data=="success"){
                $(that).closest("tr").fadeOut();
                }else{
                    alert("删除失败");
                }
            })
            }
        })
JS

)
?>
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>
</table>
</body>
</html>