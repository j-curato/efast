<?php

use yii\db\Migration;

/**
 * Class m220202_025529_add_recieved_at_in_dv_aucs_table
 */
class m220202_025529_add_recieved_at_in_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dv_aucs','recieved_at',$this->timestamp()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('dv_aucs','recieved_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220202_025529_add_recieved_at_in_dv_aucs_table cannot be reverted.\n";

        return false;
    }
    */
}
