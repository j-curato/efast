<?php

use yii\db\Migration;

/**
 * Class m230123_002739_add_amount_in_transaction_pr_items_table
 */
class m230123_002739_add_amount_in_transaction_pr_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('transaction_pr_items', 'amount', $this->decimal(15, 2)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('transaction_pr_items', 'amount');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230123_002739_add_amount_in_transaction_pr_items_table cannot be reverted.\n";

        return false;
    }
    */
}
