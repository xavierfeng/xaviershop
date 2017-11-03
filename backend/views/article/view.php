<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>后台管理</title>
</head>
<body>
<div class="container">
    <h1><?=$article->name?></h1>
    <div class="col-lg-9" style="font-size:16px;">文章分类:<?=$article->articlecategory->name?></div><div class="col-lg-3" style="font-size:16px;"><?=date("Y-m-d H:i:s",$article->create_time)?></div>
    <br />
    <hr />
    <div style="font-size:20px;text-indent:40px;"><?=$articleDetail->content?></div>
</div>
</body>
</html>