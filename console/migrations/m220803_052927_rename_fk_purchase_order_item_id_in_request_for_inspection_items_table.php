<?php

use yii\db\Migration;

/**
 * Class m220803_052927_rename_fk_purchase_order_item_id_in_request_for_inspection_items_table
 */
class m220803_052927_rename_fk_purchase_order_item_id_in_request_for_inspection_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('request_for_inspection_items', 'fk_purchase_order_item_id', 'fk_pr_purchase_order_items_aoq_item_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('request_for_inspection_items', 'fk_pr_purchase_order_items_aoq_item_id', 'fk_purchase_order_item_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220803_052927_rename_fk_purchase_order_item_id_in_request_for_inspection_items_table cannot be reverted.\n";

        return false;
    }
    */
}
