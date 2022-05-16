<?php

use yii\db\Migration;

/**
 * Class m220516_014321_alter_delivery_date_in_purchase_order_table
 */
class m220516_014321_alter_delivery_date_in_purchase_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('pr_purchase_order', 'delivery_date', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220516_014321_alter_delivery_date_in_purchase_order_table cannot be reverted.\n";

        return false;
    }
    */
}
