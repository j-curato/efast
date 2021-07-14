<?php

use yii\db\Migration;

/**
 * Class m210714_020215_add_transaction_begin_time_in_dv_aucs_table
 */
class m210714_020215_add_transaction_begin_time_in_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dv_aucs','transaction_begin_time',$this->timestamp()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('dv_aucs','transaction_begin_time');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210714_020215_add_transaction_begin_time_in_dv_aucs_table cannot be reverted.\n";

        return false;
    }
    */
}
