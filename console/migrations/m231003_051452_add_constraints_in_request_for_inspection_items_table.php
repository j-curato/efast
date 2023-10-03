<?php

use yii\db\Migration;

/**
 * Class m231003_051452_add_constraints_in_request_for_inspection_items_table
 */
class m231003_051452_add_constraints_in_request_for_inspection_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        YIi::$app->db->createCommand('SET FOREIGN_KEY_CHECKS = 0')->query();
        $this->createIndex('idx-rfi-item-fk_pr_purchase_order_items_aoq_item_id', 'request_for_inspection_items', 'fk_pr_purchase_order_items_aoq_item_id');
        $this->addForeignKey('fk-rfi-item-fk_pr_purchase_order_items_aoq_item_id', 'request_for_inspection_items', 'fk_pr_purchase_order_items_aoq_item_id', 'pr_purchase_order_items_aoq_items', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-rfi-item-fk_pr_purchase_order_items_aoq_item_id', 'request_for_inspection_items');
        $this->dropIndex('idx-rfi-item-fk_pr_purchase_order_items_aoq_item_id', 'request_for_inspection_items');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231003_051452_add_constraints_in_request_for_inspection_items_table cannot be reverted.\n";

        return false;
    }
    */
}
