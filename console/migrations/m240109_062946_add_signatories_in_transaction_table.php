<?php

use yii\db\Migration;

/**
 * Class m240109_062946_add_signatories_in_transaction_table
 */
class m240109_062946_add_signatories_in_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('transaction', 'fk_certified_by', $this->bigInteger());
        $this->createIndex('idx-transaction-fk_certified_by', 'transaction', 'fk_certified_by');
        $this->addForeignKey('fk-transaction-fk_certified_by', 'transaction', 'fk_certified_by', 'employee', 'employee_id', 'RESTRICT');
        $this->addColumn('transaction', 'fk_approved_by', $this->bigInteger());
        $this->createIndex('idx-transaction-fk_approved_by', 'transaction', 'fk_approved_by');
        $this->addForeignKey('fk-transaction-fk_approved_by', 'transaction', 'fk_approved_by', 'employee', 'employee_id', 'RESTRICT');
        $this->addColumn('transaction', 'fk_certified_budget_by', $this->bigInteger());
        $this->createIndex('idx-transaction-fk_certified_budget_by', 'transaction', 'fk_certified_budget_by');
        $this->addForeignKey('fk-transaction-fk_certified_budget_by', 'transaction', 'fk_certified_budget_by', 'employee', 'employee_id', 'RESTRICT');
        $this->addColumn('transaction', 'fk_certified_cash_by', $this->bigInteger());
        $this->createIndex('idx-transaction-fk_certified_cash_by', 'transaction', 'fk_certified_cash_by');
        $this->addForeignKey('fk-transaction-fk_certified_cash_by', 'transaction', 'fk_certified_cash_by', 'employee', 'employee_id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-transaction-fk_certified_by', 'transaction');
        $this->dropIndex('idx-transaction-fk_certified_by', 'transaction');
        $this->dropColumn('transaction', 'fk_certified_by');
        $this->dropForeignKey('fk-transaction-fk_approved_by', 'transaction');
        $this->dropIndex('idx-transaction-fk_approved_by', 'transaction');
        $this->dropColumn('transaction', 'fk_approved_by');
        $this->dropForeignKey('fk-transaction-fk_certified_budget_by', 'transaction');
        $this->dropIndex('idx-transaction-fk_certified_budget_by', 'transaction');
        $this->dropColumn('transaction', 'fk_certified_budget_by');
        $this->dropForeignKey('fk-transaction-fk_certified_cash_by', 'transaction');
        $this->dropIndex('idx-transaction-fk_certified_cash_by', 'transaction');
        $this->dropColumn('transaction', 'fk_certified_cash_by');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240109_062946_add_signatories_in_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
