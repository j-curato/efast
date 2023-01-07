<?php

use yii\db\Migration;

/**
 * Class m221228_022524_add_budget_year_and_type_in_pr_stock_table
 */
class m221228_022524_add_budget_year_and_type_in_pr_stock_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_stock', 'budget_year', $this->integer());
        $this->addColumn('pr_stock', 'cse_type', $this->string()->defaultValue('non_cse'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_stock', 'budget_year');
        $this->dropColumn('pr_stock', 'cse_type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221228_022524_add_budget_year_and_type_in_pr_stock_table cannot be reverted.\n";

        return false;
    }
    */
}
