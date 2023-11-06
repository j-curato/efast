<?php

use yii\db\Migration;

/**
 * Class m231103_020013_add_fk_requested_by_in_po_transaction_table
 */
class m231103_020013_add_fk_requested_by_in_po_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('po_transaction', 'fk_requested_by', $this->bigInteger());
        $this->createIndex('idx-po_txn-fk_requested_by', 'po_transaction', 'fk_requested_by');
        $this->addForeignKey('fk-po_txn-fk_requested_by', 'po_transaction', 'fk_requested_by', 'employee', 'employee_id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-po_txn-fk_requested_by', 'po_transaction');
        $this->dropIndex('idx-po_txn-fk_requested_by', 'po_transaction');
        $this->dropColumn('po_transaction', 'fk_requested_by');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231103_020013_add_fk_requested_by_in_po_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
