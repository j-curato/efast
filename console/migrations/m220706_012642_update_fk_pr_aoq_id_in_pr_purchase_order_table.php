<?php

use yii\db\Migration;

/**
 * Class m220706_012642_update_fk_pr_aoq_id_in_pr_purchase_order_table
 */
class m220706_012642_update_fk_pr_aoq_id_in_pr_purchase_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('pr_purchase_order','fk_pr_aoq_id',$this->bigInteger()->null());
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
        echo "m220706_012642_update_fk_pr_aoq_id_in_pr_purchase_order_table cannot be reverted.\n";

        return false;
    }
    */
}
