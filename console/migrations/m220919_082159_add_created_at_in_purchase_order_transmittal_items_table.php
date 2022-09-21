<?php

use yii\db\Migration;

/**
 * Class m220919_082159_add_created_at_in_purchase_order_transmittal_items_table
 */
class m220919_082159_add_created_at_in_purchase_order_transmittal_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('purchase_order_transmittal_items', 'create_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('purchase_order_transmittal_items', 'create_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220919_082159_add_created_at_in_purchase_order_transmittal_items_table cannot be reverted.\n";

        return false;
    }
    */
}
