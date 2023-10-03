<?php

use yii\db\Migration;

/**
 * Class m231003_054722_add_constraints_in_pr_purchase_order_items_aoq_items_table
 */
class m231003_054722_add_constraints_in_pr_purchase_order_items_aoq_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->query();

        $this->createIndex('idx-po-aoq-items-fk_purchase_order_item_id', 'pr_purchase_order_items_aoq_items', 'fk_purchase_order_item_id');
        $this->addForeignKey('fk-po-aoq-items-fk_purchase_order_item_id', 'pr_purchase_order_items_aoq_items', 'fk_purchase_order_item_id', 'pr_purchase_order_item', 'id', 'RESTRICT', 'CASCADE');

        $this->createIndex('idx-po-aoq-items-fk_aoq_entries_id', 'pr_purchase_order_items_aoq_items', 'fk_aoq_entries_id');
        $this->addForeignKey('fk-po-aoq-items-fk_aoq_entries_id', 'pr_purchase_order_items_aoq_items', 'fk_aoq_entries_id', 'pr_aoq_entries', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {


        $this->dropForeignKey('fk-po-aoq-items-fk_purchase_order_item_id', 'pr_purchase_order_items_aoq_items');
        $this->dropIndex('idx-po-aoq-items-fk_purchase_order_item_id', 'pr_purchase_order_items_aoq_items');

        $this->dropForeignKey('fk-po-aoq-items-fk_aoq_entries_id', 'pr_purchase_order_items_aoq_items');
        $this->dropIndex('idx-po-aoq-items-fk_aoq_entries_id', 'pr_purchase_order_items_aoq_items');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231003_054722_add_constraints_in_pr_purchase_order_items_aoq_items_table cannot be reverted.\n";

        return false;
    }
    */
}
