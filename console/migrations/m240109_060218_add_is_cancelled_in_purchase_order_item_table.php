<?php

use yii\db\Migration;

/**
 * Class m240109_060218_add_is_cancelled_in_purchase_order_item_table
 */
class m240109_060218_add_is_cancelled_in_purchase_order_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_purchase_order_item', 'is_cancelled', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_purchase_order_item', 'is_cancelled');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240109_060218_add_is_cancelled_in_purchase_order_item_table cannot be reverted.\n";

        return false;
    }
    */
}
