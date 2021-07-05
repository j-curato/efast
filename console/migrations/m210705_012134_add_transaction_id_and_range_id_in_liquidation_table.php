<?php

use yii\db\Migration;

/**
 * Class m210705_012134_add_transaction_id_and_range_id_in_liquidation_table
 */
class m210705_012134_add_transaction_id_and_range_id_in_liquidation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation','po_transaction_id',$this->integer());
        $this->addColumn('liquidation','check_range_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation','po_transaction');
        $this->dropColumn('liquidation','check_range_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210705_012134_add_transaction_id_and_range_id_in_liquidation_table cannot be reverted.\n";

        return false;
    }
    */
}
