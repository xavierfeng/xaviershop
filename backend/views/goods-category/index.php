<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>后台管理</title>
</head>
<body>
<table class="table table-bordered">
    <tr>
        <td>ID</td>
        <td>商品分类</td>
        <td>分类简介</td>
        <td>操作</td>
    </tr>
    <?php foreach ($goodsCategorys as $goodsCategory):?>
        <tr>
            <td><?=$goodsCategory->id?></td>
            <td><?=str_repeat("---",$goodsCategory->depth).$goodsCategory->name?></td>
            <td><?=$goodsCategory->intro?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['goods-category/update','id'=>$goodsCategory->id])?>" class="btn btn-info">编辑</a>
                <a href="javascript:;" class="btn btn-warning" id="<?=$goodsCategory->id?>">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <a href="<?=\yii\helpers\Url::to(['goods-category/add'])?>"  class="btn btn-info">添加商品分类</a>
</table>

<?php //分页工具条`
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
]);

$url=\yii\helpers\Url::to(['goods-category/delete']);
$this->registerJs(<<<JS

        $("table").on("click",".btn-warning",function(){
            if(confirm('是否确认删除')){
                 var that =this ;
                 var url = "{$url}";
            $.post(url,{"id":that.id},function(data){
                if(data=="success"){
                $(that).closest("tr").fadeOut();
                }else{
                    alert("删除失败,该分类有子分类");
                }
            })
            }
        })
JS
)

?>
</body>
</html>