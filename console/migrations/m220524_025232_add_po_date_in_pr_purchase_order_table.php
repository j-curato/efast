<?php

use yii\db\Migration;

/**
 * Class m220524_025232_add_po_date_in_pr_purchase_order_table
 */
class m220524_025232_add_po_date_in_pr_purchase_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_purchase_order', 'po_date', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_purchase_order', 'po_date');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    
    public function up()
    {

    }

    public function down()
    {
        echo "m220524_025232_add_po_date_in_pr_purchase_order_table cannot be reverted.\n";

        return false;
    }
    */
}
