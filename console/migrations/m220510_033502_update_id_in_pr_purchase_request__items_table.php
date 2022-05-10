<?php

use yii\db\Migration;

/**
 * Class m220510_033502_update_id_in_pr_purchase_request__items_table
 */
class m220510_033502_update_id_in_pr_purchase_request__items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('pr_purchase_request_item','id',$this->bigInteger());
        $this->alterColumn('pr_rfq_item','pr_purchase_request_item_id',$this->bigInteger());
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
        echo "m220510_033502_update_id_in_pr_purchase_request__items_table cannot be reverted.\n";

        return false;
    }
    */
}
