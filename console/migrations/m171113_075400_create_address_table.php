<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m171113_075400_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey()->unsigned()->notNull(),
            'member'=>$this->string(60)->comment('收货人'),
            'province'=>$this->string(60)->comment('省'),
            'city'=>$this->string(60)->comment('市'),
            'county'=>$this->string(60)->comment('县'),
            'address'=>$this->text()->comment('详细地址'),
            'tel'=>$this->char(11)->comment('电话号码'),

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
