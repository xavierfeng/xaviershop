<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m171109_101830_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey()->notNull()->unsigned(),
            'name'=>$this->string(60)->notNull()->comment('菜单名称'),
            'menu'=>$this->string(60)->notNull()->comment('上级菜单')->defaultValue(0),
            'route'=>$this->string(60)->notNull()->comment('路由'),
            'sort'=>$this->smallInteger()->notNull()->comment('排序'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
