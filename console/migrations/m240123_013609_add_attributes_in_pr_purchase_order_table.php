<?php

use yii\db\Migration;

/**
 * Class m240123_013609_add_attributes_in_pr_purchase_order_table
 */
class m240123_013609_add_attributes_in_pr_purchase_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $this->renameColumn('pr_purchase_order', 'pre_bid_conf', 'actual_proc_pre_bid_conf');
        $this->renameColumn('pr_purchase_order', 'eligibility_check', 'actual_proc_eligibility_check');
        $this->renameColumn('pr_purchase_order', 'opening_of_bids', 'actual_proc_opening_of_bids');
        $this->renameColumn('pr_purchase_order', 'bid_evaluation', 'actual_proc_bid_evaluation');
        $this->renameColumn('pr_purchase_order', 'post_qual', 'actual_proc_post_qual');

        $this->addColumn('pr_purchase_order', 'invitation_pre_bid_conf', $this->date());
        $this->addColumn('pr_purchase_order', 'invitation_eligibility_check', $this->date());
        $this->addColumn('pr_purchase_order', 'invitation_opening_of_bids', $this->date());
        $this->addColumn('pr_purchase_order', 'invitation_bid_evaluation', $this->date());
        $this->addColumn('pr_purchase_order', 'invitation_post_qual', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('pr_purchase_order', 'actual_proc_pre_bid_conf', 'pre_bid_conf');
        $this->renameColumn('pr_purchase_order', 'actual_proc_eligibility_check', 'eligibility_check');
        $this->renameColumn('pr_purchase_order', 'actual_proc_opening_of_bids', 'opening_of_bids');
        $this->renameColumn('pr_purchase_order', 'actual_proc_bid_evaluation', 'bid_evaluation');
        $this->renameColumn('pr_purchase_order', 'actual_proc_post_qual', 'post_qual');

        $this->dropColumn('pr_purchase_order', 'invitation_pre_bid_conf');
        $this->dropColumn('pr_purchase_order', 'invitation_eligibility_check');
        $this->dropColumn('pr_purchase_order', 'invitation_opening_of_bids');
        $this->dropColumn('pr_purchase_order', 'invitation_bid_evaluation');
        $this->dropColumn('pr_purchase_order', 'invitation_post_qual');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240123_013609_add_attributes_in_pr_purchase_order_table cannot be reverted.\n";

        return false;
    }
    */
}
