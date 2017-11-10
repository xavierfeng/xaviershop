<?php
/**
 * @var $this \yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
//分类名称
echo $form->field($goodsCategory,'name')->textInput();
//上级分类
//加载Ztree
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
$nodes= \yii\helpers\Json::encode(\yii\helpers\ArrayHelper::merge([['id'=>0,'parent_id'=>0,'name'=>'顶级分类']],\backend\models\GoodsCategory::getZtreeNodes()));
$this->registerJs(
    <<<JS
   var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback:{
                onClick:function(event,treeId,treeNode){
                    //获取被点击节点的id
                    var id = treeNode.id;
                    //将id写入到parent_id的值
                    $("#goodscategory-parent_id").val(id);
                }
            }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$nodes};
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            //展开所有节点
            zTreeObj.expandAll(true);
            //选中节点(回显)
            //获取节点,根据节点id搜索节点
            var node=zTreeObj.getNodeByParam('id',{$goodsCategory->parent_id},null);
            zTreeObj.selectNode(node);

JS

);
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';
echo $form->field($goodsCategory,'parent_id')->hiddenInput();
//分类简介
echo $form->field($goodsCategory,'intro')->textInput();
//提交按钮
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();