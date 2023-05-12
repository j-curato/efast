<?php

use yii\db\Migration;

/**
 * Class m230511_020016_add_columns_in_pr_aoq_table
 */
class m230511_020016_add_columns_in_pr_aoq_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_aoq', 'is_cancelled', $this->boolean()->defaultValue(0));
        $this->addColumn('pr_aoq', 'cancelled_at', $this->timestamp()->defaultValue(NULL));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_aoq', 'is_cancelled');
        $this->dropColumn('pr_aoq', 'cancelled_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230511_020016_add_columns_in_pr_aoq_table cannot be reverted.\n";

        return false;
    }
    */
}
