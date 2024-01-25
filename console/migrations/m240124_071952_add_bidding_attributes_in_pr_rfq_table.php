<?php

use yii\db\Migration;

/**
 * Class m240124_071952_add_bidding_attributes_in_pr_rfq_table
 */
class m240124_071952_add_bidding_attributes_in_pr_rfq_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_rfq', 'pre_proc_conference', $this->date());
        $this->addColumn('pr_rfq', 'philgeps_reference_num', $this->string());
        $this->addColumn('pr_rfq', 'pre_bid_conf', $this->date());
        $this->addColumn('pr_rfq', 'eligibility_check', $this->date());
        $this->addColumn('pr_rfq', 'opening_of_bids', $this->date());
        $this->addColumn('pr_rfq', 'bid_evaluation', $this->date());
        $this->addColumn('pr_rfq', 'post_qual', $this->date());
        $this->addColumn('pr_rfq', 'post_of_ib', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_rfq', 'pre_proc_conference');
        $this->dropColumn('pr_rfq', 'philgeps_reference_num');
        $this->dropColumn('pr_rfq', 'pre_bid_conf');
        $this->dropColumn('pr_rfq', 'eligibility_check');
        $this->dropColumn('pr_rfq', 'opening_of_bids');
        $this->dropColumn('pr_rfq', 'bid_evaluation');
        $this->dropColumn('pr_rfq', 'post_qual');
        $this->dropColumn('pr_rfq', 'post_of_ib');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240124_071952_add_bidding_attributes_in_pr_rfq_table cannot be reverted.\n";

        return false;
    }
    */
}
