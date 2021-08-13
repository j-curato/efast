<?php

use yii\db\Migration;

/**
 * Class m210813_070315_create_idx_uacs_in_chart_of_accounts_index
 */
class m210813_070315_create_idx_uacs_in_chart_of_accounts_index extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx_uacs','chart_of_accounts','uacs',true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_uacs','chart_of_accounts');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210813_070315_create_idx_uacs_in_chart_of_accounts_index cannot be reverted.\n";

        return false;
    }
    */
}
