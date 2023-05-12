<?php

use yii\db\Migration;

/**
 * Class m230511_024240_add_columns_in_pr_rfq_table
 */
class m230511_024240_add_columns_in_pr_rfq_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_rfq', 'is_cancelled', $this->boolean()->defaultValue(0));
        $this->addColumn('pr_rfq', 'cancelled_at', $this->timestamp()->defaultValue(NULL));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('pr_rfq', 'is_cancelled');
        $this->dropColumn('pr_rfq', 'cancelled_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230511_024240_add_columns_in_pr_rfq_table cannot be reverted.\n";

        return false;
    }
    */
}
