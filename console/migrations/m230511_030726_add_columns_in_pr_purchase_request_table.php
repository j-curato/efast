<?php

use yii\db\Migration;

/**
 * Class m230511_030726_add_columns_in_pr_purchase_request_table
 */
class m230511_030726_add_columns_in_pr_purchase_request_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('pr_purchase_request', 'is_cancel', 'is_cancelled');
        $this->addColumn('pr_purchase_request', 'cancelled_at', $this->timestamp()->defaultValue(NULL));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('pr_purchase_request', 'is_cancelled', 'is_cancel');
        $this->dropColumn('pr_purchase_request', 'cancelled_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230511_030726_add_columns_in_pr_purchase_request_table cannot be reverted.\n";

        return false;
    }
    */
}
