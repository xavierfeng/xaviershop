<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <script type="text/javascript" src="/js/jsAddress.js"></script>
    <title>收货地址</title>
	<link rel="stylesheet" href="/style/base.css" type="text/css">
	<link rel="stylesheet" href="/style/global.css" type="text/css">
	<link rel="stylesheet" href="/style/header.css" type="text/css">
	<link rel="stylesheet" href="/style/home.css" type="text/css">
	<link rel="stylesheet" href="/style/address.css" type="text/css">
	<link rel="stylesheet" href="/style/bottomnav.css" type="text/css">
	<link rel="stylesheet" href="/style/footer.css" type="text/css">

	<script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="/js/header.js"></script>
	<script type="text/javascript" src="/js/home.js"></script>
    <style>
        span.error {color:red;};
    </style>
</head>
<body>
		<!-- 顶部导航 start -->
	<div class="topnav">
		<div class="topnav_bd w1210 bc">
			<div class="topnav_left">
				
			</div>
			<div class="topnav_right fr">
				<ul>
                    <li>您好<?php if (Yii::$app->user->isGuest) {echo "游客，欢迎来到京西！[<a href=".\yii\helpers\Url::to(['member/login']).">登录</a>] [<a href=".\yii\helpers\Url::to(['member/regist']).">免费注册</a>]" ;}else{echo Yii::$app->user->identity->username."，欢迎来到京西！[<a href=".\yii\helpers\Url::to(['member/logout']).">注销</a>]";}?> </li>
					<li class="line">|</li>
					<li>我的订单</li>
					<li class="line">|</li>
					<li>客户服务</li>

				</ul>
			</div>
		</div>
	</div>
	<!-- 顶部导航 end -->
	
	<div style="clear:both;"></div>

	<!-- 头部 start -->
	<div class="header w1210 bc mt15">
		<!-- 头部上半部分 start 包括 logo、搜索、用户中心和购物车结算 -->
		<div class="logo w1210">
			<h1 class="fl"><a href="index.html"><img src="/images/logo.png" alt="京西商城"></a></h1>
			<!-- 头部搜索 start -->
			<div class="search fl">
				<div class="search_form">
					<div class="form_left fl"></div>
					<form action="" name="serarch" method="get" class="fl">
						<input type="text" class="txt" value="请输入商品关键字" /><input type="submit" class="btn" value="搜索" />
					</form>
					<div class="form_right fl"></div>
				</div>
				
				<div style="clear:both;"></div>

				<div class="hot_search">
					<strong>热门搜索:</strong>
					<a href="">D-Link无线路由</a>
					<a href="">休闲男鞋</a>
					<a href="">TCL空调</a>
					<a href="">耐克篮球鞋</a>
				</div>
			</div>
			<!-- 头部搜索 end -->

			<!-- 用户中心 start-->
			<div class="user fl">
				<dl>
					<dt>
						<em></em>
						<a href="">用户中心</a>
						<b></b>
					</dt>
					<dd>
						<div class="prompt">
                            您好，<?php if (Yii::$app->user->isGuest) {echo "游客,[<a href=".\yii\helpers\Url::to(['member/login']).">登录</a>] [<a href=".\yii\helpers\Url::to(['member/regist']).">注册</a>]" ;}else{echo Yii::$app->user->identity->username."，[<a href=".\yii\helpers\Url::to(['member/logout']).">注销</a>]";}?>
						</div>
						<div class="uclist mt10">
							<ul class="list1 fl">
								<li><a href="">用户信息></a></li>
								<li><a href="">我的订单></a></li>
								<li><a href="<?=\yii\helpers\Url::to(['address/index'])?>">收货地址></a></li>
								<li><a href="">我的收藏></a></li>
							</ul>

							<ul class="fl">
								<li><a href="">我的留言></a></li>
								<li><a href="">我的红包></a></li>
								<li><a href="">我的评论></a></li>
								<li><a href="">资金管理></a></li>
							</ul>

						</div>
						<div style="clear:both;"></div>
						<div class="viewlist mt10">
							<h3>最近浏览的商品：</h3>
							<ul>
								<li><a href=""><img src="/images/view_list1.jpg" alt="" /></a></li>
								<li><a href=""><img src="/images/view_list2.jpg" alt="" /></a></li>
								<li><a href=""><img src="/images/view_list3.jpg" alt="" /></a></li>
							</ul>
						</div>
					</dd>
				</dl>
			</div>
			<!-- 用户中心 end-->

			<!-- 购物车 start -->
			<div class="cart fl">
				<dl>
					<dt>
						<a href="">去购物车结算</a>
						<b></b>
					</dt>
					<dd>
						<div class="prompt">
							购物车中还没有商品，赶紧选购吧！
						</div>
					</dd>
				</dl>
			</div>
			<!-- 购物车 end -->
		</div>
		<!-- 头部上半部分 end -->
		
		<div style="clear:both;"></div>

		<!-- 导航条部分 start -->
		<div class="nav w1210 bc mt10">
			<!--  商品分类部分 start-->
            <div class="category fl cat1"> <!-- 非首页，需要添加cat1类 -->
                <div class="cat_hd">  <!-- 注意，首页在此div上只需要添加cat_hd类，非首页，默认收缩分类时添加上off类，鼠标滑过时展开菜单则将off类换成on类 -->
                    <h2>全部商品分类</h2>
                    <em></em>
                </div>
                <div class="cat_bd none">
                    <?php foreach ($goods_categorys1 as $goods_category1):?>
                        <div class="cat item1">
                            <h3><a href="<?=\yii\helpers\Url::to(['goods/list1','id'=>$goods_category1->id])?>"><?=$goods_category1->name?></a> <b></b></h3>
                            <div class="cat_detail">

                                <?php foreach($goods_category1->children as $goods_category2): ?>
                                <dl class="dl_1st">
                                    <dt><a href="<?=\yii\helpers\Url::to(['goods/list2','id'=>$goods_category2->id])?>"><?=$goods_category2->name?></a></dt>
                                    <?php foreach($goods_category2->children as $goods_category3): ?>
                                        <dd>
                                            <a href="<?=\yii\helpers\Url::to(['goods/list','id'=>$goods_category3->id])?>"><?=$goods_category3->name?></a>
                                        </dd>
                                    <?php endforeach; ?>
                                    <?php endforeach; ?>
                                </dl>

                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>

            </div>
			<!--  商品分类部分 end--> 

			<div class="navitems fl">
				<ul class="fl">
					<li class="current"><a href="">首页</a></li>
					<li><a href="">电脑频道</a></li>
					<li><a href="">家用电器</a></li>
					<li><a href="">品牌大全</a></li>
					<li><a href="">团购</a></li>
					<li><a href="">积分商城</a></li>
					<li><a href="">夺宝奇兵</a></li>
				</ul>
				<div class="right_corner fl"></div>
			</div>
		</div>
		<!-- 导航条部分 end -->
	</div>
	<!-- 头部 end-->
	
	<div style="clear:both;"></div>

	<!-- 页面主体 start -->
	<div class="main w1210 bc mt10">
		<div class="crumb w1210">
			<h2><strong>收货地址</strong><span>> 我的地址</span></h2>
		</div>
		
		<!-- 左侧导航菜单 start -->
		<div class="menu fl">
			<h3>我的XX</h3>
			<div class="menu_wrap">
				<dl>
					<dt>订单中心 <b></b></dt>
					<dd><b>.</b><a href="">我的订单</a></dd>
					<dd><b>.</b><a href="">我的关注</a></dd>
					<dd><b>.</b><a href="">浏览历史</a></dd>
					<dd><b>.</b><a href="">我的团购</a></dd>
				</dl>

				<dl>
					<dt>账户中心 <b></b></dt>
					<dd class="cur"><b>.</b><a href="">账户信息</a></dd>
					<dd><b>.</b><a href="">账户余额</a></dd>
					<dd><b>.</b><a href="">消费记录</a></dd>
					<dd><b>.</b><a href="">我的积分</a></dd>
					<dd><b>.</b><a href="">收货地址</a></dd>
				</dl>

				<dl>
					<dt>订单中心 <b></b></dt>
					<dd><b>.</b><a href="">返修/退换货</a></dd>
					<dd><b>.</b><a href="">取消订单记录</a></dd>
					<dd><b>.</b><a href="">我的投诉</a></dd>
				</dl>
			</div>
		</div>
		<!-- 左侧导航菜单 end -->

		<!-- 右侧内容区域 start -->
		<div  class="content fl ml10">
			<div class="address_hd" >
				<h3>收货地址薄</h3>
                <?php foreach ($addresses as $address) : ?>
				<dl>
                    <dt><?=$address->member." ".$address->province." ".$address->city." ".$address->county." ".$address->address." ".$address->tel?></dt>
                    <dd>
                        <a href="<?=\yii\helpers\Url::to(['address/update','id'=>$address->id])?>">修改</a>
                        <a href="javascript:;" id="<?=$address->id?>" class="del">删除</a>
                        <?php if($address->status==0){
                          echo  "<a href='javascript:;' id='".$address->id."' class='defalut-address'>设为默认地址</a>";
                        }else{
                            echo "<span>已设为默认地址</span>";
                        } ?>

                    </dd>
                </dl>
                    <?php endforeach; ?>
			</div>

			<div class="address_bd mt10">
				<h4>新增收货地址</h4>
				<form name="address_form" id="address_form">
						<ul>
							<li>
								<label for=""><span>*</span>收 货 人：</label>
								<input type="text" id="member" name="member" class="txt" />
							</li>
							<li>
								<label for=""><span>*</span>所在地区：</label>
                                <select id="cmbProvince" name="province"></select>
                                <select id="cmbCity" name="city"></select>
                                <select id="cmbArea" name="county"></select>
                            </li>
							<li>
								<label for=""><span>*</span>详细地址：</label>
								<input type="text" id="address" name="address" class="txt address"  />
							</li>
							<li>
								<label for=""><span>*</span>手机号码：</label>
								<input type="text" id="tel" name="tel" class="txt" />
							</li>
							<li>
								<label for="">&nbsp;</label>
								<input type="checkbox" value="1" name="status" class="check" />设为默认地址
							</li>
							<li>
								<label for="">&nbsp;</label>

							</li>
						</ul>
					</form>
                <button id="submit">保存地址</button>
			</div>	

		</div>
		<!-- 右侧内容区域 end -->
	</div>
	<!-- 页面主体 end-->

	<div style="clear:both;"></div>

	<!-- 底部导航 start -->
        <div class="bottomnav w1210 bc mt10">
            <?php foreach ($article_categorys as $article_category): ?>
                <div class="bnav<?=$article_category->id?>">
                    <h3><b></b> <em><?=$article_category->name?></em></h3>
                    <ul>
                        <?php foreach($article_category->children as $article):?>
                            <li><a href=""></a><?=$article->name?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
	<!-- 底部导航 end -->

	<div style="clear:both;"></div>
	<!-- 底部版权 start -->
	<div class="footer w1210 bc mt10">
		<p class="links">
			<a href="">关于我们</a> |
			<a href="">联系我们</a> |
			<a href="">人才招聘</a> |
			<a href="">商家入驻</a> |
			<a href="">千寻网</a> |
			<a href="">奢侈品网</a> |
			<a href="">广告服务</a> |
			<a href="">移动终端</a> |
			<a href="">友情链接</a> |
			<a href="">销售联盟</a> |
			<a href="">京西论坛</a>
		</p>
		<p class="copyright">
			 © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号 
		</p>
		<p class="auth">
			<a href=""><img src="/images/xin.png" alt="" /></a>
			<a href=""><img src="/images/kexin.jpg" alt="" /></a>
			<a href=""><img src="/images/police.jpg" alt="" /></a>
			<a href=""><img src="/images/beian.gif" alt="" /></a>
		</p>
	</div>
	<!-- 底部版权 end -->
<script src="http://static.runoob.com/assets/jquery-validation-1.14.0/lib/jquery.js"></script>
<script src="http://static.runoob.com/assets/jquery-validation-1.14.0/dist/jquery.validate.min.js"></script>
<script type="text/javascript">

    $().ready(function() {
// 在键盘按下并释放及提交后验证提交表单
        $("#address_form").validate({
            rules: {
                member: {
                    required: true,
                },
                address: {
                    required: true,
                },
                tel: {
                    required: true,
                    digits:true,
                    tel:true,
                },
            },
            messages: {
                member: {
                    required: "请填写收货人",
                },
                address: {
                    required:"请填写收货地址",
                },
                tel: {
                    required: "请输入手机号码",
                    digits:"请输入正确的手机号",
                },
            },
            errorElement:"span",
        })
    });
    //自定义手机验证
    jQuery.validator.addMethod("tel", function(value, element) {
        var tel = /^13[0-9]{1}[0-9]{8}|^15[1-9]{1}[0-9]{8}|^18[1-9]{1}[0-9]{8}/;
        return (tel.test(value));
    }, "请正确填写手机号码");
    //省市县三级联动
    addressInit('cmbProvince', 'cmbCity', 'cmbArea');

    $("#submit").click(function(){
        var url ="<?=\yii\helpers\Url::to(['address/add'])?>";
            $.post(url,$('#address_form').serialize(),function(data){
                    if(data=="success"){
                        alert("添加地址成功");
                        location.reload();
                    }else{
                        alert("添加地址失败");
                    }
            })
    })

    //修改默认地址
    $(".defalut-address").click(function(){
        var url ="<?=\yii\helpers\Url::to(['address/default-address'])?>";
        var that = this;
        if(confirm("确认设置为默认地址?")){
            $.post(url,{id:that.id},function(data){
                if(data=="success"){
                    alert("设置成功");
                    location.reload();
                }else{
                    alert("设置失败")
                }
            })
        }

    });

    //删除
    $(".del").click(function(){
        var url ="<?=\yii\helpers\Url::to(['address/delete'])?>";
        var that = this;
        if(confirm("确认删除吗")){
            $.post(url,{id:that.id},function(data){
                if(data=="success"){
                    alert("删除成功");
                    $(that).closest("dl").fadeOut();
                }else{
                    alert("删除失败")
                }
            })
        }

    })
</script>
</body>
</html>