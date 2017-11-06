<?php
/////////////article文章////////////////
namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;
class ArticleController extends Controller
{
    //列表功能
    public function actionIndex()
    {
        //分页工具类
        $query= Article::find()->where('status>=0');
        $pager = new Pagination();
        $pager->pageSize = 3;
        $pager->totalCount=$query->count();
        $articles=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['articles'=>$articles,'pager'=>$pager]);
    }

    //添加文章分类
    public function actionAdd()
    {
        $request = new Request();
        //实例化表单类
        $article = new Article();
        $articleDetail = new ArticleDetail();
        if($request->getIsPost()){
            //接受表单数据
            $article->load($request->post());
            $articleDetail->load($request->post());
            //验证表单数据
            if($article->validate()&&$articleDetail->validate()){
                //验证通过
                //保存文章表信息
                $article->create_time=time();
                $article->save(false);
                //获取新增文章id
                $articleDetail->article_id=$article->id;
                $articleDetail->save();
                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect(['article/index']);
            }else{
                //验证未通过
                //获取错误信息
                $error=$article->getErrors();
                //显示错误信息
                var_dump($error);
            }
        }else{
            //显示添加页面
            $articleCate = new ArticleCategory();
            $category = $articleCate->getArticleCategory();
            return $this->render('edit',['article'=>$article,'articleDetail'=>$articleDetail,'category'=>$category]);
        }
    }

    //修改文章分类
    public function actionUpdate($id)
    {
        $request = new Request();
        //数据库查询对应id数据
        $article = Article::findOne(['id' => $id]);
        $articleDetail = ArticleDetail::findOne(['article_id'=>$id]);
        if($request->getIsPost()){
            //接受表单数据
            $article->load($request->post());
            $articleDetail->load($request->post());
            //验证表单数据
            //文章和文章详情都需要验证通过
            if($article->validate()&&$articleDetail->validate()){
                //验证通过
                $article->save(false);
                $articleDetail->save();
                //跳转
                \Yii::$app->session->setFlash('success','修改成功');
                $this->redirect(['article/index']);
            }else{
                //验证未通过
                //获取错误信息
                $error=$article->getErrors();
                //显示错误信息
                var_dump($error);
            }
        }else{
            //显示添加页面
            $articleCate = new ArticleCategory();
            $category = $articleCate->getArticleCategory();
            return $this->render('edit',['article'=>$article,'articleDetail'=>$articleDetail,'category'=>$category]);
        }
    }

    //文章查看
    public function actionView($id)
    {
        $article = Article::findOne(['id'=>$id]);
        $articleDetail = ArticleDetail::findOne(['article_id'=>$id]);
        return $this->render('view',['article'=>$article,'articleDetail'=>$articleDetail]);
    }

    //删除文章分类
    public function actionDelete()
    {
        $id =\Yii::$app->request->post('id');
        $article=Article::findOne(['id'=>$id]);
        if($article){
            $article->status = -1;
            $article->save(false);
            //跳转
            echo 'success';
        }
    }
}