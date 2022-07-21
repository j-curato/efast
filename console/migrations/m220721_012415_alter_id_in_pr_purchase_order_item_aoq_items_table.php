<?php

use yii\db\Migration;

/**
 * Class m220721_012415_alter_id_in_pr_purchase_order_item_aoq_items_table
 */
class m220721_012415_alter_id_in_pr_purchase_order_item_aoq_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {   
        $this->alterColumn('pr_purchase_order_items_aoq_items','id',$this->bigInteger());
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
        echo "m220721_012415_alter_id_in_pr_purchase_order_item_aoq_items_table cannot be reverted.\n";

        return false;
    }
    */
}
