<?php

use yii\db\Migration;

/**
 * Class m220531_013329_add_date_work_begun_and_date_completed_purchase_order_table
 */
class m220531_013329_add_date_work_begun_and_date_completed_purchase_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_purchase_order','date_work_begun',$this->date());
        $this->addColumn('pr_purchase_order','date_completed',$this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_purchase_order','date_work_begun');
        $this->dropColumn('pr_purchase_order','date_completed');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220531_013329_add_date_work_begun_and_date_completed_purchase_order_table cannot be reverted.\n";

        return false;
    }
    */
}
