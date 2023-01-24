<?php

use yii\db\Migration;

/**
 * Class m230119_062244_add_is_cancel_in_pr_purchase_request_table
 */
class m230119_062244_add_is_cancel_in_pr_purchase_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_purchase_request', 'is_cancel', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_purchase_request', 'is_cancel');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230119_062244_add_is_cancel_in_pr_purchase_request_table cannot be reverted.\n";

        return false;
    }
    */
}
