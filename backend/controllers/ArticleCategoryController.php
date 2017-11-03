<?php
/////////////articlecategory文章分类////////////////
namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class ArticleCategoryController extends Controller
{
    //列表功能
    public function actionIndex()
    {
        //分页工具类
        $query= ArticleCategory::find()->where('status>=0');
        $pager = new Pagination();
        $pager->pageSize = 3;
        $pager->totalCount=$query->count();
        $articleCates=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['articleCates'=>$articleCates,'pager'=>$pager]);
    }

    //添加文章分类
    public function actionAdd()
    {
        $request = new Request();
        //实例化表单类
        $articleCate = new ArticleCategory();
        if($request->getIsPost()){
            //接受表单数据
            $articleCate->load($request->post());
            //验证表单数据
            if($articleCate->validate()){
                //验证通过
                $articleCate->save(false);
                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect(['article-category/index']);
            }else{
                //验证未通过
                //获取错误信息
                $error=$articleCate->getErrors();
                //显示错误信息
                var_dump($error);
            }
        }else{
            //显示添加页面
            return $this->render('edit',['articleCate'=>$articleCate]);
        }
    }

    //修改文章分类
    public function actionUpdate($id)
    {
        $request = new Request();
        //数据库查询对应id数据
        $articleCate = ArticleCategory::findOne(['id' => $id]);
        if($request->getIsPost()){
            //接受表单数据
            $articleCate->load($request->post());
            //验证表单数据
            if($articleCate->validate()){
                //验证通过
                $articleCate->save(false);
                //跳转
                \Yii::$app->session->setFlash('success','修改成功');
                $this->redirect(['article-category/index']);
            }else{
                //验证未通过
                //获取错误信息
                $error=$articleCate->getErrors();
                //显示错误信息
                var_dump($error);
            }
        }else{
            //显示添加页面
            return $this->render('edit',['articleCate'=>$articleCate]);
        }
    }

    //删除文章分类
    public function actionDelete()
    {
        $id =\Yii::$app->request->post('id');
        $articleCate=ArticleCategory::findOne(['id'=>$id]);
        if($articleCate){
            $articleCate->status = -1;
            $articleCate->save(false);
            echo 'success';
        }
    }
}