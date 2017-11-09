<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>首页</title>
</head>
<body>
<table class="table table-bordered">
    <tr>
        <td>角色名称</td>
        <td>角色描述</td>
        <td>操作</td>
    </tr>
    <?php foreach ($roles as $role):?>
        <tr>
            <td><?=$role->name?></td>
            <td><?=$role->description?></td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['auth/del-role','id'=>$role->name])?>" class="btn btn-danger">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
    <a href="<?=\yii\helpers\Url::to(['auth/add-role'])?>"  class="btn btn-info">添加角色</a>
</table>
