<?php

use yii\db\Migration;

/**
 * Class m230720_065552_alter_id_in_pr_aoq_entries_table
 */
class m230720_065552_alter_id_in_pr_aoq_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('pr_purchase_order_items_aoq_items', 'fk_aoq_entries_id', $this->bigInteger());
        $this->alterColumn('pr_aoq_entries', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230720_065552_alter_id_in_pr_aoq_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
