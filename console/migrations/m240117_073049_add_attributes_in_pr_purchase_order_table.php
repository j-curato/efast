<?php

use yii\db\Migration;

/**
 * Class m240117_073049_add_attributes_in_pr_purchase_order_table
 */
class m240117_073049_add_attributes_in_pr_purchase_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_purchase_order', 'pre_proc_conference', $this->date());
        $this->addColumn('pr_purchase_order', 'philgeps_reference_num', $this->string());
        $this->addColumn('pr_purchase_order', 'pre_bid_conf', $this->date());
        $this->addColumn('pr_purchase_order', 'eligibility_check', $this->date());
        $this->addColumn('pr_purchase_order', 'opening_of_bids', $this->date());
        $this->addColumn('pr_purchase_order', 'bid_evaluation', $this->date());
        $this->addColumn('pr_purchase_order', 'post_qual', $this->date());
        $this->addColumn('pr_purchase_order', 'bac_resolution_award', $this->date());
        $this->addColumn('pr_purchase_order', 'notice_of_award', $this->date());
        $this->addColumn('pr_purchase_order', 'contract_signing', $this->date());
        $this->addColumn('pr_purchase_order', 'notice_to_proceed', $this->date());
        $this->addColumn('pr_purchase_order', 'mooe_amount', $this->decimal(10, 2));
        $this->addColumn('pr_purchase_order', 'co_amount', $this->decimal(10, 2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_purchase_order', 'pre_proc_conference');
        $this->dropColumn('pr_purchase_order', 'philgeps_reference_num');
        $this->dropColumn('pr_purchase_order', 'pre_bid_conf');
        $this->dropColumn('pr_purchase_order', 'eligibility_check');
        $this->dropColumn('pr_purchase_order', 'opening_of_bids');
        $this->dropColumn('pr_purchase_order', 'bid_evaluation');
        $this->dropColumn('pr_purchase_order', 'post_qual');
        $this->dropColumn('pr_purchase_order', 'bac_resolution_award');
        $this->dropColumn('pr_purchase_order', 'notice_of_award');
        $this->dropColumn('pr_purchase_order', 'contract_signing');
        $this->dropColumn('pr_purchase_order', 'notice_to_proceed');
        $this->dropColumn('pr_purchase_order', 'mooe_amount');
        $this->dropColumn('pr_purchase_order', 'co_amount');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240117_073049_add_attributes_in_pr_purchase_order_table cannot be reverted.\n";

        return false;
    }
    */
}
