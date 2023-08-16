<?php

use yii\db\Migration;

/**
 * Class m230816_012315_add_note_in_fund_source_table
 */
class m230816_012315_add_note_in_fund_source_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('fund_source', 'note', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('fund_source', 'note');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230816_012315_add_note_in_fund_source_table cannot be reverted.\n";

        return false;
    }
    */
}
