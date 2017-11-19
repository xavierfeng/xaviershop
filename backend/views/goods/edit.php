<?php
$form = \yii\bootstrap\ActiveForm::begin();
//商品名称
echo $form->field($goods,'name')->textInput();
//LOGO
echo $form->field($goods,'logo')->hiddenInput();
/*
 * WebUploader
 * 注册css和js文件
 */
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js',[
    'depends'=>\yii\web\JqueryAsset::className(),//指定依赖关系,webuploader.js必须在jquery后面加载(依赖于jquery)
//    'position'=>\yii\web\View::POS_END //指定加载文件的位置
]);
$url = \yii\helpers\Url::to(['uploads']);
$this->registerJs(
    <<<JS
    // 初始化Web Uploader
    var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    swf:'/js/Uploader.swf',

    // 文件接收服务端。
    server: '{$url}',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',

    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif,'
    }
});
// 文件上传成功，回显图片
uploader.on( 'uploadSuccess', function( file,response) {
    //上传文件的路径response.url
    //将图片地址赋值给img标签
    $('#img').attr('src',response.url);
    //图片地址赋值给logo
    $('#goods-logo').val(response.url)
});
JS

)
?>
    <div id="uploader-demo">
        <!--用来存放item-->
        <div id="fileList" class="uploader-list"></div>
        <div id="filePicker">选择图片</div>
        <div><img id="img" /></div>
    </div>

<?php
//商品分类
echo $form->field($goods,'goods_category_id')->hiddenInput();
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
                    $("#goods-goods_category_id").val(id);
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
            var node=zTreeObj.getNodeByParam('id',{$goods->goods_category_id},null);
            zTreeObj.selectNode(node);
JS

);
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';
//品牌分类
echo $form->field($goods,'brand_id')->dropDownList($brands);
//市场价格
echo $form->field($goods,'market_price')->textInput();
//商品价格
echo $form->field($goods,'shop_price')->textInput();
//库存
echo $form->field($goods,'stock')->textInput();
//是否上架
echo $form->field($goods,'is_on_sale')->inline()->radioList(['0'=>'下架','1'=>'在售']);
//商品详情
echo $form->field($goodsIntro,'content')->widget('kucha\ueditor\UEditor',[
    'clientOptions' => [
        //编辑区域大小
        'initialFrameHeight' => '200',
        //设置语言
        'lang' =>'en', //中文为 zh-cn
    ]]);
//验证码
echo $form->field($goods,'code')->widget(\yii\captcha\Captcha::className(),['template'=>'<div class="row"><div class="col-lg-2">{input}</div><div class="col-lg-2">{image}</div></div>']);
//提交按钮
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();