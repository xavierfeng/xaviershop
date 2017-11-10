<?php
//////////菜单管理/////////////
namespace backend\controllers;

use backend\models\Menu;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class MenuController extends Controller
{
    //菜单列表
    public function actionIndex()
    {
        //分页工具类
        $query= Menu::find();
        $pager = new Pagination();
        $pager->pageSize = 10;
        $pager->totalCount=$query->count();
        $menus=$query->limit($pager->limit)->offset($pager->offset)->orderBy(['menu'=>SORT_ASC])->all();
        return $this->render('index',['menus'=>$menus,'pager'=>$pager]);
    }

    //菜单添加
    public function actionAdd()
    {
        $request = new Request();
        $menu = new Menu();
        $auth = \Yii::$app->authManager;
        if($request->getIsPost()){
            //接受表单数据
            $menu->load($request->post());
            //验证表单数据
            if($menu->validate()){
                //验证通过
                //保存数据
                $menu->save();
                //跳转
                \Yii::$app->session->setFlash('success','添加成功');
                $this->redirect(['menu/index']);
            }
        }
        //所有路由
        $routes=ArrayHelper::map($auth->getPermissions(),'name','name');
        $menus=$menu->getMenus();
        return $this->render('edit',['menu'=>$menu,'routes'=>$routes,'menus'=>$menus]);
    }

    //菜单修改
    public function actionUpdate($id)
    {
        $request = new Request();
        $menu = Menu::findOne(['id'=>$id]);
        $auth = \Yii::$app->authManager;
        if($request->getIsPost()){
            //接受表单数据
            $menu->load($request->post());
            //验证表单数据
            if($menu->validate()){
                //验证通过
                //保存数据
                $menu->save();
                //跳转
                \Yii::$app->session->setFlash('success','修改成功');
                $this->redirect(['menu/index']);
            }
        }
        //所有路由
        $routes=ArrayHelper::map($auth->getPermissions(),'name','name');
        $menus=$menu->getMenus();
        return $this->render('edit',['menu'=>$menu,'routes'=>$routes,'menus'=>$menus]);
    }

    //菜单删除
    public function actionDelete()
    {
        $id=\Yii::$app->request->post('id');
        $menu = Menu::findOne(['id'=>$id]);
        if($menu){
            $menu->delete();
            return 'success';
        }else{
            return false;
        }
    }

}