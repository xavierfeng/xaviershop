<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>后台管理</title>
</head>
<body>
<table class="table table-bordered">
    <tr>
        <td>ID</td>
        <td>货号</td>
        <td>商品名称</td>
        <td>商品价格</td>
        <td>库存</td>
        <td>创建时间</td>
        <td>LOGO</td>
        <td>操作</td>
    </tr>
    <?php foreach ($goodsList as $goods):?>
        <tr>
            <td><?=$goods->id?></td>
            <td><?=$goods->sn?></td>
            <td><?=$goods->name?></td>
            <td><?=$goods->shop_price?></td>
            <td><?=$goods->stock?></td>
            <td><?=date("Y-m-d H:i:s",$goods->create_time)?></td>
            <td><?=\yii\bootstrap\Html::img($goods->logo,['width'=>50,'height'=>50])?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['goods/gallery','id'=>$goods->id])?>" class="btn btn-info">相册</a>
                <a href="<?=\yii\helpers\Url::to(['goods/update','id'=>$goods->id])?>" class="btn btn-warning">编辑</a>
                <a href="javascript:;" class="btn btn-danger" id="<?=$goods->id?>">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <form id="search" action="/goods/index" method="get" role="form">
        <input type="text" name="searchName" placeholder="商品名"  style="width:150px;">
        <input type="text" name="searchSn" placeholder="货号"  style="width:150px;">
        <input type="text" name="searchLft" placeholder="￥"  style="width:150px;">
        <input type="text" name="searchRgt" placeholder="￥"  style="width:150px;">
        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span>搜索</button>
    </form>
</table>

<?php //分页工具条`
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
]);

$url=\yii\helpers\Url::to(['goods/delete']);
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