<?php

use yii\db\Migration;

/**
 * Class m231116_070327_add_is_deleted_in_purchase_order_transmittal_items_table
 */
class m231116_070327_add_is_deleted_in_purchase_order_transmittal_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('purchase_order_transmittal_items', 'is_deleted', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('purchase_order_transmittal_items', 'is_deleted');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231116_070327_add_is_deleted_in_purchase_order_transmittal_items_table cannot be reverted.\n";

        return false;
    }
    */
}
