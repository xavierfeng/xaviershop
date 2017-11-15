<?php
namespace frontend\controllers;

use backend\models\ArticleCategory;
use backend\models\Goods;
use backend\models\GoodsCategory;
use yii\web\Controller;

class GoodsController extends Controller
{

    //一级分类
    public function actionList1()
    {

        $id=\Yii::$app->request->get('id');
        $query=Goods::find();
        $goodsCategory1 = GoodsCategory::findOne(['id'=>$id]);
        //查询所有二级分类
        foreach ($goodsCategory1->children as $goodsCateogory2){
            //所有三级分类
            foreach ($goodsCateogory2->children as $goodsCateogory3){
                $query->orWhere(['goods_category_id'=>$goodsCateogory3->id]);
            }
        }
        $goodsList=$query->all();
        $article_categorys= ArticleCategory::find()->all();
        $goods_categorys1=GoodsCategory::find()->where(['parent_id'=>0])->all();
        $goods_category=GoodsCategory::findOne(['id'=>$id]);
        return $this->render('list',['goodsList'=>$goodsList,'goods_categorys1'=>$goods_categorys1,'goods_category'=>$goods_category,'article_categorys'=>$article_categorys]);
    }


    //二级分类
    public function actionList2()
    {
        $id=\Yii::$app->request->get('id');
        $query=Goods::find();
        $goodsCategory2 = GoodsCategory::findOne(['id'=>$id]);
            //所有三级分类
            foreach ($goodsCategory2->children as $goodsCateogory3){
                $query->orWhere(['goods_category_id'=>$goodsCateogory3->id]);
            }
        $goodsList=$query->all();
        $article_categorys= ArticleCategory::find()->all();
        $goods_categorys1=GoodsCategory::find()->where(['parent_id'=>0])->all();
        $goods_category=GoodsCategory::findOne(['id'=>$id]);
        return $this->render('list',['goodsList'=>$goodsList,'goods_categorys1'=>$goods_categorys1,'goods_category'=>$goods_category,'article_categorys'=>$article_categorys]);
    }

    //三级分类
    public function actionList()
    {
        $id=\Yii::$app->request->get('id');
        $article_categorys= ArticleCategory::find()->all();
        $goods_categorys1=GoodsCategory::find()->where(['parent_id'=>0])->all();
        $goodsList = Goods::find()->where(['goods_category_id'=>$id])->all();
        $goods_category=GoodsCategory::findOne(['id'=>$id]);
        return $this->render('list',['goodsList'=>$goodsList,'goods_categorys1'=>$goods_categorys1,'goods_category'=>$goods_category,'article_categorys'=>$article_categorys]);
    }

    public function actionGoods()
    {
        $id=\Yii::$app->request->get('id');
        $article_categorys= ArticleCategory::find()->all();
        $goods_categorys1=GoodsCategory::find()->where(['parent_id'=>0])->all();
        $goods= Goods::find()->where(['id'=>$id])->one();
        return $this->render('goods',['goods'=>$goods,'goods_categorys1'=>$goods_categorys1,'article_categorys'=>$article_categorys]);
    }
}