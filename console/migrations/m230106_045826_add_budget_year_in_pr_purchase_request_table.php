<?php

use yii\db\Migration;

/**
 * Class m230106_045826_add_budget_year_in_pr_purchase_request_table
 */
class m230106_045826_add_budget_year_in_pr_purchase_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_purchase_request', 'budget_year', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_purchase_request', 'budget_year');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230106_045826_add_budget_year_in_pr_purchase_request_table cannot be reverted.\n";

        return false;
    }
    */
}
