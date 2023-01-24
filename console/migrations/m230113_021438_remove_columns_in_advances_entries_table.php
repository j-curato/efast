<?php

use yii\db\Migration;

/**
 * Class m230113_021438_remove_columns_in_advances_entries_table
 */
class m230113_021438_remove_columns_in_advances_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // $this->dropColumn('advances_entries', 'sub_account1_id');
        // $this->dropColumn('advances_entries', 'division');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // $this->addColumn('advances_entries', 'sub_account1_id', $this->integer());
        // $this->addColumn('advances_entries', 'division', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230113_021438_remove_columns_in_advances_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
