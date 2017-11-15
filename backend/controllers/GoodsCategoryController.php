<?php
//////////////商品分类/////////////////
namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\Request;

class GoodsCategoryController extends Controller
{


    //商品分类列表
    public function actionIndex()
    {
        //分页工具类
        $query= GoodsCategory::find()->orderBy(['tree'=>SORT_ASC,'lft'=>SORT_ASC]);
        $pager = new Pagination();
        $pager->pageSize = 10;
        $pager->totalCount=$query->count();
        $goodsCategorys=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['goodsCategorys'=>$goodsCategorys,'pager'=>$pager]);


    }
    //添加商品分类
    public function actionAdd()
    {
        $goodsCategory = new GoodsCategory();
        //parent_id设置默认值
        $goodsCategory->parent_id=0;
        $request = new Request();
        if($request->getIsPost()){
            //接受表单数据
            $goodsCategory->load($request->post());
            //验证表单数据
            if($goodsCategory->validate()){
                //验证通过
                //保存数据
                if($goodsCategory->parent_id == 0){
                    //创建根节点
                    $goodsCategory->makeRoot(false);
                    //$goodsCategory->save(false); 不能使用save
                    //跳转
                    \Yii::$app->session->setFlash('success','添加根节点成功');
                    $this->redirect(['goods-category/index']);
                }else{
                    //创建子节点
                    $parent = GoodsCategory::findOne(['id'=>$goodsCategory->parent_id]);
                    $goodsCategory->prependTo($parent,false);
                    //跳转
                    \Yii::$app->session->setFlash('success','添加子节点成功');
                    $this->redirect(['goods-category/index']);
                }

            }
        }else {
            return $this->render('edit', ['goodsCategory'=>$goodsCategory]);
        }
    }

    //修改商品分类
    public function actionUpdate($id)
    {
        $goodsCategory = GoodsCategory::findOne(['id'=>$id]);
        //parent_id设置默认值
        $request = new Request();
        if($request->getIsPost()){
            //接受表单数据
            $goodsCategory->load($request->post());
            //验证表单数据
            if($goodsCategory->validate()){
                //验证通过
                //保存数据
                if($goodsCategory->parent_id == 0){
                    //修改根节点
                    //根节点修改为根节点报错--单独处理 旧parent_id=0
                    if($goodsCategory->getOldAttribute('parent_id')==0){
                        $goodsCategory->save(false);
                    }else{
                        $goodsCategory->makeRoot(false);
                    }
                    //跳转
                    \Yii::$app->session->setFlash('success','编辑根节点成功');
                    $this->redirect(['goods-category/index']);
                }else{
                    //创建子节点
                    $parent = GoodsCategory::findOne(['id'=>$goodsCategory->parent_id]);
                    $goodsCategory->prependTo($parent,false);
                    //跳转
                    \Yii::$app->session->setFlash('success','编辑子节点成功');
                    $this->redirect(['goods-category/index']);
                }

            }
        }else {
            return $this->render('edit', ['goodsCategory'=>$goodsCategory]);
        }
    }

    //删除商品分类
    public function actionDelete(){
        //判断该分类是否有子节点
        $request = new Request();
        $id=$request->post();
        $goodsCategory = GoodsCategory::findOne(['id'=>$id]);
            //判断该节点是否是叶子节点(没有儿子)
            if($goodsCategory->isLeaf()){
                //判断该节点是否是根节点
                if($goodsCategory->parent_id != 0){
                    $goodsCategory->delete();
                    return 'success';
                }else{
                    $goodsCategory->deleteWithChildren();
                    return 'success';
                }
            }else{
                return "false";
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