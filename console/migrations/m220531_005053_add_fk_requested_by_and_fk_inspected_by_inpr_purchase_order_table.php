<?php

use yii\db\Migration;

/**
 * Class m220531_005053_add_fk_requested_by_and_fk_inspected_by_inpr_purchase_order_table
 */
class m220531_005053_add_fk_requested_by_and_fk_inspected_by_inpr_purchase_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_purchase_order', 'fk_requested_by', $this->bigInteger());
        $this->addColumn('pr_purchase_order', 'fk_inspected_by', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_purchase_order', 'fk_requested_by');
        $this->dropColumn('pr_purchase_order', 'fk_inspected_by');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220531_005053_add_fk_requested_by_and_fk_inspected_by_inpr_purchase_order_table cannot be reverted.\n";

        return false;
    }
    */
}
