<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>后台管理</title>
</head>
<body>
<table class="table table-bordered">
    <tr>
        <td>图片ID</td>
        <td>内容</td>
        <td>操作</td>
    </tr>
    <?php foreach ($goodsGallerys as $goodsGallery):?>
        <tr>
            <td><?=$goodsGallery->goods_id?></td>
            <td><?=\yii\bootstrap\Html::img($goodsGallery->path)?></td>
            <td>
                <a href="javascript:;" class="btn btn-danger" id="<?=$goodsGallery->id?>">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <a href="<?=\yii\helpers\Url::to(['goods/gallery-add','id'=>$goodsGallery->goods_id])?>"  class="btn btn-info">添加相册</a>
</table>
</table>

<?php
$url=\yii\helpers\Url::to(['goods/gallery-delete']);
$this->registerJs(<<<JS

        $("table").on("click",".btn-danger",function(){
            if(confirm('是否确认删除')){
                 var that =this ;
                 var url = "{$url}";
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
</body>
</html>