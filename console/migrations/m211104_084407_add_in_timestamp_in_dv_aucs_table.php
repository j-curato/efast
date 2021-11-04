<?php

use yii\db\Migration;

/**
 * Class m211104_084407_add_in_timestamp_in_dv_aucs_table
 */
class m211104_084407_add_in_timestamp_in_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dv_aucs','in_timestamp',$this->timestamp()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('dv_aucs','in_timestamp');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211104_084407_add_in_timestamp_in_dv_aucs_table cannot be reverted.\n";

        return false;
    }
    */
}
