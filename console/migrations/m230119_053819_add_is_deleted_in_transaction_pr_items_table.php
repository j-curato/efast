<?php

use yii\db\Migration;

/**
 * Class m230119_053819_add_is_deleted_in_transaction_pr_items_table
 */
class m230119_053819_add_is_deleted_in_transaction_pr_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('transaction_pr_items', 'is_deleted', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('transaction_pr_items', 'is_deleted');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230119_053819_add_is_deleted_in_transaction_pr_items_table cannot be reverted.\n";

        return false;
    }
    */
}
