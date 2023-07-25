<?php

use yii\db\Migration;

/**
 * Class m230724_074948_add_back_date_and_new_pr_number_in_pr_purchase_request_table
 */
class m230724_074948_add_back_date_and_new_pr_number_in_pr_purchase_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_purchase_request', 'back_date', $this->date());
        $this->addColumn('pr_purchase_request', 'new_pr_number', $this->string()->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_purchase_request', 'back_date');
        $this->dropColumn('pr_purchase_request', 'new_pr_number');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230724_074948_add_back_date_and_new_pr_number_in_pr_purchase_request_table cannot be reverted.\n";

        return false;
    }
    */
}
