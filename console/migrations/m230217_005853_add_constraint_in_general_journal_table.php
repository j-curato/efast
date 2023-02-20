<?php

use yii\db\Migration;

/**
 * Class m230217_005853_add_constraint_in_general_journal_table
 */
class m230217_005853_add_constraint_in_general_journal_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-gen-jour-book_id', 'general_journal', 'book_id');
        $this->addForeignKey('fk-gen-jour-book_id', 'general_journal', 'book_id', 'books', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-gen-jour-book_id', 'general_journal');
        $this->dropIndex('idx-gen-jour-book_id', 'general_journal');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230217_005853_add_constraint_in_general_journal_table cannot be reverted.\n";

        return false;
    }
    */
}
