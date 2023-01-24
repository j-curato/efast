<?php

use yii\db\Migration;

/**
 * Class m230113_011859_remove_columns_in_advances_table
 */
class m230113_011859_remove_columns_in_advances_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('advances', 'particular');
        $this->dropColumn('advances', 'book_id');
        $this->dropColumn('advances', 'advances_type');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->addColumn('advances', 'particular', $this->text());
        $this->addColumn('advances', 'book_id', $this->integer());
        $this->addColumn('advances', 'advances_type', $this->text());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230113_011859_remove_columns_in_advances_table cannot be reverted.\n";

        return false;
    }
    */
}
