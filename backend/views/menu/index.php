<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>后台管理</title>
</head>
<body>
<h1>菜单列表</h1>
<table class="table table-bordered">
    <tr>
        <td>菜单名称</td>
        <td>路由</td>
        <td>排序</td>
        <td>操作</td>
    </tr>
    <?php foreach ($menus as $menu):?>
        <tr>
            <td><?=$menu->name?></td>
            <td><?=$menu->route?></td>
            <td><?=$menu->sort?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['menu/update','id'=>$menu->id])?>" class="btn btn-warning">编辑</a>
                <a href="javascript:;" class="btn btn-danger" id="<?=$menu->id?>">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php //分页工具条`
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
]);

$url=\yii\helpers\Url::to(['menu/delete']);
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