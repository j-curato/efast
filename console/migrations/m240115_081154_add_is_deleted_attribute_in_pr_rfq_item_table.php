<?php

use yii\db\Migration;

/**
 * Class m240115_081154_add_is_deleted_attribute_in_pr_rfq_item_table
 */
class m240115_081154_add_is_deleted_attribute_in_pr_rfq_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_rfq_item', 'is_deleted', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_rfq_item', 'is_deleted');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240115_081154_add_is_deleted_attribute_in_pr_rfq_item_table cannot be reverted.\n";

        return false;
    }
    */
}
