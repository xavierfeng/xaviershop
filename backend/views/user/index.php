<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>后台管理</title>
</head>
<body>
<table class="table table-bordered">
    <tr>
        <td>ID</td>
        <td>用户名</td>
        <td>邮箱</td>
        <td>状态</td>
        <td>最后登录时间</td>
        <td>最后登录IP</td>
        <td>操作</td>
    </tr>
    <?php foreach ($users as $user):?>
        <tr>
            <td><?=$user->id?></td>
            <td><?=$user->username?></td>
            <td><?=$user->email?></td>
            <td>
                <?=(($user->status)==0)?"禁用":"";?>
                <?=(($user->status)==10)?"启用":"";?>
            </td>
            <td><?=(date("Y-m-d H:i:s",$user->last_login_time)=="1970-01-01 08:00:00")?"未登录":date("Y-m-d H:i:s",$user->last_login_time)?></td>
            <td><?=$user->last_login_ip?></td>
            <td>
                <?php if(Yii::$app->user->can('user/update')){?>
                <a href="<?=\yii\helpers\Url::to(['user/update','id'=>$user->id])?>" class="btn btn-info">编辑</a>
                <?php } ?>
                <?php if(Yii::$app->user->can('user/delete')){?>
                <a href="javascript:;" class="btn btn-warning" id="<?=$user->id?>">删除</a>
                <?php } ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <a href="<?=\yii\helpers\Url::to(['user/add'])?>"  class="btn btn-info">添加用户</a>
</table>

<?php //分页工具条`
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$pager,
]);

$url=\yii\helpers\Url::to(['user/delete']);
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