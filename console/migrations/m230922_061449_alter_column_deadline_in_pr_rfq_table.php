<?php

use yii\db\Migration;

/**
 * Class m230922_061449_alter_column_deadline_in_pr_rfq_table
 */
class m230922_061449_alter_column_deadline_in_pr_rfq_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('pr_rfq', 'deadline', $this->timestamp());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('pr_rfq', 'deadline', $this->date());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230922_061449_alter_column_deadline_in_pr_rfq_table cannot be reverted.\n";

        return false;
    }
    */
}
