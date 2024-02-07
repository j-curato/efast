<?php

use yii\db\Migration;

/**
 * Class m240206_060812_add_constraints_in_transaction_pr_items_table
 */
class m240206_060812_add_constraints_in_transaction_pr_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS =0")->query();
        $this->createIndex('idx-transaction_pr_items-fk_pr_allotment_id', 'transaction_pr_items', 'fk_pr_allotment_id');
        $this->addForeignKey('fk-transaction_pr_items-fk_pr_allotment_id', 'transaction_pr_items', 'fk_pr_allotment_id', 'pr_purchase_request_allotments', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-transaction_pr_items-fk_pr_allotment_id', 'transaction_pr_items');
        $this->dropIndex('idx-transaction_pr_items-fk_pr_allotment_id', 'transaction_pr_items');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240206_060812_add_constraints_in_transaction_pr_items_table cannot be reverted.\n";

        return false;
    }
    */
}
