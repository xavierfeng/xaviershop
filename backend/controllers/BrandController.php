<?php
/////////////brand品牌////////////////
namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class BrandController extends Controller
{
    public  $enableCsrfValidation = false;
    //列表功能
    public function actionIndex()
    {
        //分页工具类
        $query= Brand::find()->where('status>=0');
        $pager = new Pagination();
        $pager->pageSize = 3;
        $pager->totalCount=$query->count();
        $brandList=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['brandList'=>$brandList,'pager'=>$pager]);
    }

    //添加品牌
    public function actionAdd()
    {
        $request = new Request();
        //实例化表单类
        $brand = new Brand();
        if($request->getIsPost()){
            //接受表单数据
            $brand->load($request->post());
            //验证表单数据
            if($brand->validate()){
                //验证通过
                //保存数据
                //设置取消save的验证
                $brand->save(false);
                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect(['brand/index']);
            }else{
                //验证未通过
                //获取错误信息
                $error=$brand->getErrors();
                //显示错误信息
                var_dump($error);
            }
        }else{
            //显示添加页面
            return $this->render('edit',['brand'=>$brand]);
        }
    }

    //处理ajax图片上传
    public function actionUpload()
    {
        if(\Yii::$app->request->isPost){
            $imgFile = UploadedFile::getInstanceByName('file');
            //判断是否有文件上传
            if($imgFile){
                $fileName='/upload/brand/'.uniqid().'.'.$imgFile->extension;
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

    //修改品牌
    public function actionUpdate($id)
    {
        $request = new Request();
        //数据库查询对应id数据
        $brand = Brand::findOne(['id' => $id]);
        if ($request->getIsPost()) {
            //接受表单提交的数据
            $brand->load($request->post());
            //验证数据
            if ($brand->validate()) {
                //验证通过
                //获取上传文件的扩展名
                $brand->save(false);
                //跳转页面修改成功
                \Yii::$app->session->setFlash('success', '修改成功');
                return $this->redirect(['brand/index']);
            } else {
                //验证未通过
                //获取错误信息
                $error = $brand->getErrors();
                //显示错误信息
                var_dump($error);exit;
            }
        }
        //显示修改页面
        return $this->render('edit', ['brand' => $brand]);
    }

    //删除品牌
    public function actionDelete()
    {
        $id =\Yii::$app->request->post('id');
        $brand=Brand::findOne(['id'=>$id]);
        if($brand){
            $brand->status = -1;
            $brand->save(false);
            echo 'success';
        }
    }
}