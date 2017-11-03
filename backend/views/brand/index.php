<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>后台管理</title>
</head>
<body>
<table class="table table-bordered">
    <tr>
        <td>ID</td>
        <td>品牌名称</td>
        <td>品牌简介</td>
        <td>LOGO</td>
        <td>状态</td>
        <td>操作</td>
    </tr>
    <?php foreach ($brandList as $brand):?>
        <tr>
            <td><?=$brand->id?></td>
            <td><?=$brand->name?></td>
            <td><?=$brand->intro?></td>
            <td><?=\yii\bootstrap\Html::img($brand->logo,['width'=>50,'height'=>50])?></td>
            <td>
                <?=(($brand->status)==0)?"隐藏":"";?>
                <?=(($brand->status)==1)?"显示":"";?>
            </td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['brand/update','id'=>$brand->id])?>" class="btn btn-info">编辑</a>
                <a href="javascript:;" class="btn btn-warning" id="<?=$brand->id?>">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <a href="<?=\yii\helpers\Url::to(['brand/add'])?>"  class="btn btn-info">添加品牌</a>
</table>

<?php //分页工具条`
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
]);

$url=\yii\helpers\Url::to(['brand/delete']);
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