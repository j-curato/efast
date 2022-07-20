<?php

use yii\db\Migration;

/**
 * Class m220720_082806_alter_pr_purchase_order_items_table
 */
class m220720_082806_alter_pr_purchase_order_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('pr_purchase_order_item', 'id', $this->bigInteger());
        $this->dropColumn('pr_purchase_order_item', 'fk_pr_aoq_entries_id');
        $this->dropColumn('pr_purchase_order_item', 'is_lowest');
        $this->addColumn('pr_purchase_order_item', 'serial_number', $this->string()->notNull());
        $this->addColumn('pr_purchase_order_item', 'payee_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('pr_purchase_order_item', 'fk_pr_aoq_entries_id', $this->bigInteger());
        $this->addColumn('pr_purchase_order_item', 'is_lowest', $this->bigInteger());
        $this->dropColumn('pr_purchase_order_item', 'serial_number');
        $this->dropColumn('pr_purchase_order_item', 'payee_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220720_082806_alter_pr_purchase_order_items_table cannot be reverted.\n";

        return false;
    }
    */
}
