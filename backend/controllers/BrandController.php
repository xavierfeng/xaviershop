<?php
/////////////brand品牌////////////////
namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends Controller
{
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
            //将上传文件封装成uploadedfile对象
            $brand->imgFile = UploadedFile::getInstance($brand,'imgFile');
            //验证表单数据
            if($brand->validate()){
                //验证通过
                //获取上传文件的扩展名
                $ext=$brand->imgFile->extension;
                //设置保存文件路径
                $file = '/upload/brand/'.uniqid().'.'.$ext;
                $brand->imgFile->saveAs(\Yii::getAlias('@webroot').$file,0);
                $brand->logo=$file;
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

    //修改品牌
    public function actionUpdate($id)
    {
        $request = new Request();
        //数据库查询对应id数据
        $brand = Brand::findOne(['id' => $id]);
        $logo=$brand->logo;

        if ($request->getIsPost()) {
            //接受表单提交的数据
            $brand->load($request->post());
            //将上传文件封装成uploadedfile对象
            $brand->imgFile = UploadedFile::getInstance($brand,'imgFile');
            //验证数据
            if ($brand->validate()) {
                //验证通过
                //获取上传文件的扩展名
                if($brand->imgFile){//如果上传图片 保存新图片
                    $ext = $brand->imgFile->extension;
                    //设置保存文件路径
                    $file = '/upload/admin/' . uniqid() . '.' . $ext;
                    $brand->imgFile->saveAs(\Yii::getAlias('@webroot') . $file, 0);
                    $brand->logo = $file;
                }else{//否则保留旧图片地址
                    $brand->logo = $logo;
                }
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