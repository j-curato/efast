<?php

use yii\db\Migration;

/**
 * Class m230105_021149_add_fk_supplemental_ppmp_noncse_and_cse_id_on_pr_purchase_request_table
 */
class m230105_021149_add_fk_supplemental_ppmp_noncse_and_cse_id_on_pr_purchase_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_purchase_request', 'fk_supplemental_ppmp_noncse_id', $this->integer());
        $this->addColumn('pr_purchase_request', 'fk_supplemental_ppmp_cse_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_purchase_request', 'fk_supplemental_ppmp_noncse_id');
        $this->dropColumn('pr_purchase_request', 'fk_supplemental_ppmp_cse_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230105_021149_add_fk_supplemental_ppmp_noncse_and_cse_id_on_pr_purchase_request_table cannot be reverted.\n";

        return false;
    }
    */
}
