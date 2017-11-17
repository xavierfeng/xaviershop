<?php
namespace backend\models;

use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;

class GoodsCategory extends ActiveRecord
{

    //获取zTree需要的数据
    public static function getZtreeNodes()
    {
        return self::find()->select(['id','name','parent_id'])->asArray()->all();

    }
    //设置属性标签名称
    public function attributeLabels()
    {
        return [
            'name'=>'分类名称',
            'intro'=>'分类简介',
            'parent_id'=>'上级分类',
        ];
    }
    //设置验证规则
    public function rules()
    {
        return [
            ['name','required'],
            ['parent_id','required'],
            ['intro','required'],
        ];
    }

    //建立一级分类和二级分类的关系 一对多
    public function getChildren()
    {
        //儿子menu => 父亲id
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }

    //找爸爸
    public function getParent()
    {
        return $this->hasOne(self::class,['id'=>'parent_id']);
    }

    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',//必须打开
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }

    public static function getGoodsCategorys()
    {
        //使用redis进行性能优化
        //缓存使用 先读缓存,有就直接用,没有就重新生成
        $redis= new \Redis();
        $redis->connect('127.0.0.1');
        //先读缓存
        $html=$redis->get('goodscategory');
        //没有就重新生成
        if($html==false){
            $goods_categorys1=GoodsCategory::find()->where(['parent_id'=>0])->all();
            $html='<div class="cat_bd">';
            foreach ($goods_categorys1 as $k1=> $goods_category1){
                $html.='<div class="cat '.($k1==0?'item1':'').'">';
                $html.='<h3><a href="'.\yii\helpers\Url::to(['goods/list','id'=>$goods_category1->id]).'">'.$goods_category1->name.'</a><b></b></h3>';
                $html.='<div class="cat_detail">';
                foreach($goods_category1->children as $k2 => $goods_category2){
                $html.='<dl '.($k2==0?'class="dl_1st"':'').'>';
                    $html.='<dt><a href="'.\yii\helpers\Url::to(['goods/list','id'=>$goods_category2->id]).'">'.$goods_category2->name.'</a></dt>';
                    foreach($goods_category2->children as $goods_category3){
                        $html.='<dd><a href="'.\yii\helpers\Url::to(['goods/list','id'=>$goods_category3->id]).'">'.$goods_category3->name.'</a></dd>';
                    }
                }
                $html.='</dl>';
                $html.='</div>';
                $html.='</div>';
            }
            $html.='</div>';
            //保存到redis 一天更新一次
            $redis->set('goodscategory',$html,24*3600);
        }
        return $html;
    }
}