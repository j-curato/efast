<?php

use yii\db\Migration;

/**
 * Class m230405_005447_add_constraints_in_purchase_request_table
 */
class m230405_005447_add_constraints_in_purchase_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-pr-book_id', 'pr_purchase_request', 'book_id');
        $this->createIndex('idx-pr-requested_by_id', 'pr_purchase_request', 'requested_by_id');
        $this->createIndex('idx-pr-approved_by_id', 'pr_purchase_request', 'approved_by_id');


        $this->addForeignKey('fk-pr-book_id', 'pr_purchase_request', 'book_id', 'books', 'id', 'RESTRICT');
        $this->addForeignKey('fk-pr-requested_by_id', 'pr_purchase_request', 'requested_by_id', 'employee', 'employee_id', 'RESTRICT');
        $this->addForeignKey('fk-pr-approved_by_id', 'pr_purchase_request', 'approved_by_id', 'employee', 'employee_id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-pr-book_id', 'pr_purchase_request');
        $this->dropForeignKey('fk-pr-requested_by_id', 'pr_purchase_request');
        $this->dropForeignKey('fk-pr-approved_by_id', 'pr_purchase_request');

        $this->dropIndex('idx-pr-book_id', 'pr_purchase_request');
        $this->dropIndex('idx-pr-requested_by_id', 'pr_purchase_request');
        $this->dropIndex('idx-pr-approved_by_id', 'pr_purchase_request');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230405_005447_add_constraints_in_purchase_request_table cannot be reverted.\n";

        return false;
    }
    */
}
