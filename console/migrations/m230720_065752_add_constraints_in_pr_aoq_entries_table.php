<?php

use yii\db\Migration;

/**
 * Class m230720_065752_add_constraints_in_pr_aoq_entries_table
 */
class m230720_065752_add_constraints_in_pr_aoq_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-aoq-entry-pr_aoq_id', 'pr_aoq_entries', 'pr_aoq_id');
        $this->addForeignKey('fk-aoq-entry-pr_aoq_id', 'pr_aoq_entries', 'pr_aoq_id', 'pr_aoq', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-aoq-entry-pr_aoq_id', 'pr_aoq_entries');
        $this->dropIndex('idx-aoq-entry-pr_aoq_id', 'pr_aoq_entries');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230720_065752_add_constraints_in_pr_aoq_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
