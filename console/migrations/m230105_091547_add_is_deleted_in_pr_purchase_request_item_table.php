<?php

use yii\db\Migration;

/**
 * Class m230105_091547_add_is_deleted_in_pr_purchase_request_item_table
 */
class m230105_091547_add_is_deleted_in_pr_purchase_request_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_purchase_request_item', 'is_deleted', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_purchase_request_item', 'is_deleted');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230105_091547_add_is_deleted_in_pr_purchase_request_item_table cannot be reverted.\n";

        return false;
    }
    */
}
