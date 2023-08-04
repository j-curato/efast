<?php

use yii\db\Migration;

/**
 * Class m230804_021545_add_created_by_in_pr_purchase_request_table
 */
class m230804_021545_add_created_by_in_pr_purchase_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_purchase_request', 'fk_created_by', $this->bigInteger());
        $this->createIndex('idx-purchase-request-fk_created_by', 'pr_purchase_request', 'fk_created_by');
        $this->addForeignKey('fk-purchase-request-fk_created_by', 'pr_purchase_request', 'fk_created_by', 'user', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-purchase-request-fk_created_by', 'pr_purchase_request');
        $this->dropIndex('idx-purchase-request-fk_created_by', 'pr_purchase_request');
        $this->dropColumn('pr_purchase_request', 'fk_created_by');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230804_021545_add_created_by_in_pr_purchase_request_table cannot be reverted.\n";

        return false;
    }
    */
}
