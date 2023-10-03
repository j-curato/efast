<?php

use yii\db\Migration;

/**
 * Class m231003_014642_add_is_deleted_in_pr_aoq_entries_table
 */
class m231003_014642_add_is_deleted_in_pr_aoq_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_aoq_entries', 'is_deleted', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_aoq_entries', 'is_deleted');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231003_014642_add_is_deleted_in_pr_aoq_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
