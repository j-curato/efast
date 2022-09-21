<?php

use yii\db\Migration;

/**
 * Class m220919_082100_add_date_in_purchase_order_transmittal_table
 */
class m220919_082100_add_date_in_purchase_order_transmittal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('purchase_order_transmittal', 'date', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('purchase_order_transmittal', 'date');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220919_082100_add_date_in_purchase_order_transmittal_table cannot be reverted.\n";

        return false;
    }
    */
}
