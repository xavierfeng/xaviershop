<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>首页</title>
</head>
<body>
<?php
/*
 * DataTables
 * 注册css和js文件
 */
$this->registerCssFile('@web/dataTables/media/css/jquery.dataTables.css');
$this->registerJsFile('@web/dataTables/media/js/jquery.dataTables.js',[
    'depends'=>\yii\web\JqueryAsset::className(),
]);
?>
<table  id="table_id" class="table table-bordered display">
    <thead>
    <tr>
        <td>角色名称</td>
        <td>角色描述</td>
        <td>操作</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($roles as $role):?>
        <tr>
            <td><?=$role->name?></td>
            <td><?=$role->description?></td>
            <td>
                <?php if(Yii::$app->user->can('auth/update-role')){?>
                <a href="<?=\yii\helpers\Url::to(['auth/update-role','roleName'=>$role->name])?>" class="btn btn-warning">编辑</a>
                <?php } ?>
                <?php if(Yii::$app->user->can('auth/del-role')){?>
                <a href="<?=\yii\helpers\Url::to(['auth/del-role','roleName'=>$role->name])?>" class="btn btn-danger">删除</a>
                <?php } ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php
$this->registerJs(
    <<<JS
  $(document).ready( function () {
    $('#table_id').DataTable({
    language: {
        "sProcessing": "处理中...",
        "sLengthMenu": "显示 _MENU_ 项结果",
        "sZeroRecords": "没有匹配结果",
        "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
        "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
        "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
        "sInfoPostFix": "",
        "sSearch": "搜索:",
        "sUrl": "",
        "sEmptyTable": "表中数据为空",
        "sLoadingRecords": "载入中...",
        "sInfoThousands": ",",
        "oPaginate": {
            "sFirst": "首页",
            "sPrevious": "上页",
            "sNext": "下页",
            "sLast": "末页"
        },
        "oAria": {
            "sSortAscending": ": 以升序排列此列",
            "sSortDescending": ": 以降序排列此列"
        }
    }
});
    
} );
    

JS

);
