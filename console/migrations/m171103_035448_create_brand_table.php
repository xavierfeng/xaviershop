<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m171103_035448_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'name'=>$this->string(50)->comment('名称'),
            'intro'=>$this->text()->comment('简介'),
            'logo'=>$this->string()->comment('LOGO图片'),
            'sort'=>$this->integer(11)->comment('排序'),
            'status'=>$this->integer(2)->comment('状态(-1删除0隐藏1正常)')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
