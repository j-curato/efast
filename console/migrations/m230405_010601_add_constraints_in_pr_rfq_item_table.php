<?php

use yii\db\Migration;

/**
 * Class m230405_010601_add_constraints_in_pr_rfq_item_table
 */
class m230405_010601_add_constraints_in_pr_rfq_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-rfq_itm-pr_rfq_id', 'pr_rfq_item', 'pr_rfq_id');
        $this->createIndex('idx-rfq_itm-pr_purchase_request_item_id', 'pr_rfq_item', 'pr_purchase_request_item_id');

        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->query();
        $this->addForeignKey('fk-rfq_itm-pr_rfq_id', 'pr_rfq_item', 'pr_rfq_id', 'pr_rfq', 'id', 'CASCADE');
        $this->addForeignKey('fk-rfq_itm-pr_purchase_request_item_id', 'pr_rfq_item', 'pr_purchase_request_item_id', 'pr_purchase_request_item', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-rfq_itm-pr_rfq_id', 'pr_rfq_item');
        $this->dropForeignKey('fk-rfq_itm-pr_purchase_request_item_id', 'pr_rfq_item');

        $this->dropIndex('idx-rfq_itm-pr_rfq_id', 'pr_rfq_item');
        $this->dropIndex('idx-rfq_itm-pr_purchase_request_item_id', 'pr_rfq_item');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230405_010601_add_constraints_in_pr_rfq_item_table cannot be reverted.\n";

        return false;
    }
    */
}
