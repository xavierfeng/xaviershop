<?php
/////////商品//////////////
namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class GoodsController extends  Controller
{
    public  $enableCsrfValidation = false;
    //商品列表
    public function actionIndex()
    {
        //分页工具类
            $name=\Yii::$app->request->get('searchName');
            $sn=\Yii::$app->request->get('searchSn');
            $lft=\Yii::$app->request->get('searchLft');
            $rgt=\Yii::$app->request->get('searchRgt');
            $query= Goods::find();
            if($name){
                $query->andWhere(['like','name',$name]);
            }if($sn){
                $query->andWhere(['sn'=>$sn]);
            }if($lft){
                $query->andWhere(['>=','shop_price',$lft]);
            }if($rgt){
                $query->andWhere(['<=','shop_price',$rgt]);
            }
            $pager = new Pagination();
            $pager->pageSize = 3;
            $pager->totalCount=$query->count();
            $goodsList=$query->andwhere('status=1')->limit($pager->limit)->offset($pager->offset)->all();
            return $this->render('index',['goodsList'=>$goodsList,'pager'=>$pager]);
    }

    //商品添加功能
    public function actionAdd()
    {
        $request = new Request();
        $goods = new Goods();
        $goodsIntro = new GoodsIntro();
        if($request->getIsPost()){
            //接受表单数据
            $goods->load($request->post());
            $goodsIntro->load($request->post());
            //验证表单数据
            if($goods->validate()&&$goodsIntro->validate()){
                //验证通过
                //保存数据
                $goods->status = 1;
                $goods->create_time = time();
                $day = date("Ymd",time());
                //保存商品每日添加表
                //如果当日已经保存则直接修改数量
                $goodsDayCount = GoodsDayCount::find()->where(['day'=>$day])->one();
                if($goodsDayCount){
                    $goodsDayCount->count+=1;
                    $goodsDayCount->save();
                    $goods->sn =str_pad($day,12,0,STR_PAD_RIGHT).$goodsDayCount->count;
                }else{
                    //不存在 创建当日记录 保存数量为1
                    $newGoodsDayCount = new GoodsDayCount();
                    $newGoodsDayCount->day = date("Y-m-d",time());
                    $newGoodsDayCount->count=1;
                    $newGoodsDayCount->save();
                    $goods->sn =str_pad($day,12,0,STR_PAD_RIGHT)."1";
                }
                //设置取消save的验证
                $goods->save(false);
                $goodsIntro->goods_id=$goods->id;
                $goodsIntro->save(false);
                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect(['goods/index']);
            }
        }else{
            //返回所有品牌
            $brands = new Brand();
            $brands = $brands->getBrands();
            $goods->goods_category_id=0;
            //显示添加页面
            return $this->render('edit',['goods'=>$goods,'goodsIntro'=>$goodsIntro  ,'brands'=>$brands]);
        }
    }

    //商品修改功能
    public function actionUpdate($id)
    {
        $request = new Request();
        $goods = Goods::findOne(['id'=>$id]);
        $goodsIntro =GoodsIntro::findOne(['goods_id'=>$id]);
        if($request->getIsPost()){
            //接受表单数据
            $goods->load($request->post());
            $goodsIntro->load($request->post());
            //验证表单数据
            if($goods->validate()&&$goodsIntro->validate()){
                //验证通过
                //保存数据
                //设置取消save的验证
                $goods->save(false);
                $goodsIntro->save(false);
                //跳转
                \Yii::$app->session->setFlash('success','修改成功');
                $this->redirect(['goods/index']);
            }
        }else{
            //返回所有品牌
            $brands = new Brand();
            $brands = $brands->getBrands();
            //显示添加页面
            return $this->render('edit',['goods'=>$goods,'goodsIntro'=>$goodsIntro  ,'brands'=>$brands]);
        }
    }

    //ueditor
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => \Yii::$app->params['backend_domain'].'/upload',//图片访问路径前缀
                    "imagePathFormat" => "/image/{yyyy}{mm}{dd}/{time}{rand:6}",//上传保存路径
                    "imageRoot" => \Yii::getAlias("@webroot").'/upload'
                ]
            ]
        ];
    }

    //处理ajax图片上传
    public function actionUploads()
    {
        if(\Yii::$app->request->isPost){
            $imgFile = UploadedFile::getInstanceByName('file');
            //判断是否有文件上传
            if($imgFile){
                $fileName='/upload/goods/'.uniqid().'.'.$imgFile->extension;
                $imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,0);
                //////////上传到七牛云/////////////////
                // 需要填写你的 Access Key 和 Secret Key
                $accessKey ="pYV0geM1JFOCb-x8ffvP3fyM5kTR5pFHvLraZLRm";
                $secretKey ="quqcx2zGBa9HSDj6bLoX7sD0kPXo_U1vEBJ-JW7L";
                $bucket = "yii2shop";

                // 构建鉴权对象
                $auth = new Auth($accessKey, $secretKey);

                // 生成上传 Token
                $token = $auth->uploadToken($bucket);

                // 要上传文件的本地路径
                $filePath =\Yii::getAlias('@webroot').$fileName;

                // 上传到七牛后保存的文件名
                $key = $fileName;
                $qiniu = 'oyxfgeniz.bkt.clouddn.com';
                // 初始化 UploadManager 对象并进行文件的上传。
                $uploadMgr = new UploadManager();

                // 调用 UploadManager 的 putFile 方法进行文件的上传。
                list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
                if ($err !== null) {
                    return Json::encode(['error'=>$err]);
                } else {
                    //返回图片地址
                    return Json::encode(['url'=>'http://'.$qiniu."/".$fileName]);
                }
                //////////上传到七牛云/////////////////
            }
        }

    }

    //商品删除(逻辑删除)
    public function actionDelete()
    {
        $id =\Yii::$app->request->post('id');
        $goods = Goods::findOne(['id'=>$id]);
        if($goods){
            $goods->status= 0 ;
            $goods->save(false);
            return 'success';
        }else{
            return false;
        }
    }

    //商品相册
    public function actionGallery($id)
    {
        $goodsGallerys = GoodsGallery::find()->where(['goods_id'=>$id])->all();
        return $this->render('gallery',['goodsGallerys'=>$goodsGallerys,'goods_id'=>$id]);
    }

    //处理ajax图片上传
    public function actionUploadGallery()
    {
        if(\Yii::$app->request->isPost){
            $imgFile = UploadedFile::getInstanceByName('file');
            //判断是否有文件上传
            if($imgFile){
                $fileName='/upload/goods/gallery/'.uniqid().'.'.$imgFile->extension;
                $imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,0);
                //////////上传到七牛云/////////////////
                // 需要填写你的 Access Key 和 Secret Key
                $accessKey ="pYV0geM1JFOCb-x8ffvP3fyM5kTR5pFHvLraZLRm";
                $secretKey ="quqcx2zGBa9HSDj6bLoX7sD0kPXo_U1vEBJ-JW7L";
                $bucket = "yii2shop";

                // 构建鉴权对象
                $auth = new Auth($accessKey, $secretKey);

                // 生成上传 Token
                $token = $auth->uploadToken($bucket);

                // 要上传文件的本地路径
                $filePath =\Yii::getAlias('@webroot').$fileName;

                // 上传到七牛后保存的文件名
                $key = $fileName;
                $qiniu = 'oyxfgeniz.bkt.clouddn.com';
                // 初始化 UploadManager 对象并进行文件的上传。
                $uploadMgr = new UploadManager();

                // 调用 UploadManager 的 putFile 方法进行文件的上传。
                list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
                if ($err !== null) {
                    return Json::encode(['error'=>$err]);
                } else {
                    //保存数据到gallery表
                    $goodsGallery = new GoodsGallery();
                    $path='http://'.$qiniu."/".$fileName;
                    $goodsId=\Yii::$app->request->get('id');
                    $goodsGallery->path=$path;
                    $goodsGallery->goods_id=$goodsId;
                    $goodsGallery->save();
                    $id=$goodsGallery->id;
                    //返回图片地址
                    return Json::encode(['url'=>$path,'id'=>$id]);
                }
                //////////上传到七牛云/////////////////
            }
        }
    }

    //商品相册删除
    public function actionGalleryDelete()
    {
        $id=\Yii::$app->request->post('id');
        $gallery = GoodsGallery::findOne(['id'=>$id]);
        if($gallery){
            $gallery->delete();
            return 'success';
        }
    }

    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }
}