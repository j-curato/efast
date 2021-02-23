<?php

use yii\db\Migration;

/**
 * Class m210223_024523_add_current_noncurrent_to_jev_accounting_entries_table
 */
class m210223_024523_add_current_noncurrent_to_jev_accounting_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('jev_accounting_entries', 'current_noncurrent', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('jev_accounting_entries','current_noncurrent');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210223_024523_add_current_noncurrent_to_jev_accounting_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
