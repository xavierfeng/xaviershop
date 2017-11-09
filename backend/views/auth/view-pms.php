<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>首页</title>
</head>
<body>
<table class="table table-bordered">
    <tr>
        <td>权限名称</td>
        <td>权限描述</td>
        <td>操作</td>
    </tr>
    <?php foreach ($permissions as $permission):?>
        <tr>
            <td><?=$permission->name?></td>
            <td><?=$permission->description?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['auth/del-pms','id'=>$permission->name])?>" class="btn btn-danger">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <a href="<?=\yii\helpers\Url::to(['auth/add-pms'])?>"  class="btn btn-info">添加权限</a>
</table>
