<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>后台管理</title>
</head>
<body>
<table class="table table-bordered">
    <tr>
        <td>ID</td>
        <td>文章分类</td>
        <td>分类简介</td>
        <td>状态</td>
        <td>操作</td>
    </tr>
    <?php foreach ($articleCates as $articleCate):?>
        <tr>
            <td><?=$articleCate->id?></td>
            <td><?=$articleCate->name?></td>
            <td><?=$articleCate->intro?></td>
            <td>
                <?=(($articleCate->status)==0)?"隐藏":"";?>
                <?=(($articleCate->status)==1)?"显示":"";?>
            </td>
            <td>
                <?php if(Yii::$app->user->can('article-category/update')){?>
                <a href="<?=\yii\helpers\Url::to(['article-category/update','id'=>$articleCate->id])?>" class="btn btn-info">编辑</a>
                <?php } ?>
                <?php if(Yii::$app->user->can('article-category/delete')){?>
                <a href="javascript:;" class="btn btn-warning" id="<?=$articleCate->id?>">删除</a>
                <?php } ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <a href="<?=\yii\helpers\Url::to(['article-category/add'])?>"  class="btn btn-info">添加文章分类</a>
</table>

<?php //分页工具条`
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
]);

$url=\yii\helpers\Url::to(['article-category/delete']);
$this->registerJs(<<<JS

        $("table").on("click",".btn-warning",function(){
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