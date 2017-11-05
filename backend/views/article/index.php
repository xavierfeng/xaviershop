<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>后台管理</title>
</head>
<body>
<table class="table table-bordered">
    <tr>
        <td>ID</td>
        <td>文章名称</td>
        <td>文章简介</td>
        <td>文章分类</td>
        <td>文章状态</td>
        <td>创建时间</td>
        <td>操作</td>
    </tr>
    <?php foreach ($articles as $article):?>
        <tr>
            <td><?=$article->id?></td>
            <td><?=$article->name?></td>
            <td><?=$article->intro?></td>
            <td><?=$article->articlecategory->name?></td>
            <td>
                <?=(($article->status)==0)?"隐藏":"";?>
                <?=(($article->status)==1)?"显示":"";?>
            </td>
            <td><?=date('Y-m-d H:i:s',$article->create_time)?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['article/view','id'=>$article->id])?>" class="btn btn-info">查看</a>
                <a href="<?=\yii\helpers\Url::to(['article/update','id'=>$article->id])?>" class="btn btn-warning">编辑</a>
                <a href="javascript:;" class="btn btn-danger" id="<?=$article->id?>">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <a href="<?=\yii\helpers\Url::to(['article/add'])?>"  class="btn btn-info">添加文章</a>
</table>

<?php //分页工具条`
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
]);

$url=\yii\helpers\Url::to(['article/delete']);
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