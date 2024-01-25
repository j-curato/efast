<?php

use yii\db\Migration;

/**
 * Class m240125_070336_remove_attributes_in_purchase_order_table
 */
class m240125_070336_remove_attributes_in_purchase_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('pr_purchase_order', 'actual_proc_pre_bid_conf');
        $this->dropColumn('pr_purchase_order', 'actual_proc_eligibility_check');
        $this->dropColumn('pr_purchase_order', 'actual_proc_opening_of_bids');
        $this->dropColumn('pr_purchase_order', 'actual_proc_bid_evaluation');
        $this->dropColumn('pr_purchase_order', 'actual_proc_post_qual');
        $this->dropColumn('pr_purchase_order', 'pre_proc_conference');
        $this->dropColumn('pr_purchase_order', 'philgeps_reference_num');
        $this->dropColumn('pr_purchase_order', 'post_of_ib');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('pr_purchase_order', 'actual_proc_pre_bid_conf', $this->date());
        $this->addColumn('pr_purchase_order', 'actual_proc_eligibility_check', $this->date());
        $this->addColumn('pr_purchase_order', 'actual_proc_opening_of_bids', $this->date());
        $this->addColumn('pr_purchase_order', 'actual_proc_bid_evaluation', $this->date());
        $this->addColumn('pr_purchase_order', 'actual_proc_post_qual', $this->date());
        $this->addColumn('pr_purchase_order', 'pre_proc_conference', $this->date());
        $this->addColumn('pr_purchase_order', 'philgeps_reference_num', $this->date());
        $this->addColumn('pr_purchase_order', 'post_of_ib', $this->date());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240125_070336_remove_attributes_in_purchase_order_table cannot be reverted.\n";

        return false;
    }
    */
}
