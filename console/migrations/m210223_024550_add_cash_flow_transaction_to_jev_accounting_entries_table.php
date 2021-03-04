<?php

use yii\db\Migration;

/**
 * Class m210223_024550_add_cash_flow_transaction_to_jev_accounting_entries_table
 */
class m210223_024550_add_cash_flow_transaction_to_jev_accounting_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('jev_accounting_entries', 'cash_flow_transaction_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('jev_accounting_entries', 'cash_flow_transaction_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210223_024550_add_cash_flow_transaction_to_jev_accounting_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
