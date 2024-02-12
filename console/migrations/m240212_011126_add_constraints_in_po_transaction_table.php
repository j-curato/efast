<?php

use yii\db\Migration;

/**
 * Class m240212_011126_add_constraints_in_po_transaction_table
 */
class m240212_011126_add_constraints_in_po_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-po_responsibility_center_id-po_transaction', 'po_transaction', 'po_responsibility_center_id');
        $this->addForeignKey(
            'fk-po_responsibility_center_id-po_transaction',
            'po_transaction',
            'po_responsibility_center_id',
            'po_responsibility_center',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-po_responsibility_center_id-po_transaction',
            'po_transaction'
        );
        $this->dropIndex('idx-po_responsibility_center_id-po_transaction', 'po_transaction');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240212_011126_add_constraints_in_po_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
