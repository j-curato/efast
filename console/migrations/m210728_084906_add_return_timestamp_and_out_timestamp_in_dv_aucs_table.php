<?php

use yii\db\Migration;

/**
 * Class m210728_084906_add_return_timestamp_and_out_timestamp_in_dv_aucs_table
 */
class m210728_084906_add_return_timestamp_and_out_timestamp_in_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dv_aucs','return_timestamp',$this->timestamp()->null());
        $this->addColumn('dv_aucs','out_timestamp',$this->timestamp()->null());
        $this->addColumn('dv_aucs','accept_timestamp',$this->timestamp()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('dv_aucs','return_timestamp');
        $this->dropColumn('dv_aucs','out_timestamp');
        $this->dropColumn('dv_aucs','accept_timestamp');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210728_084906_add_return_timestamp_and_out_timestamp_in_dv_aucs_table cannot be reverted.\n";

        return false;
    }
    */
}
