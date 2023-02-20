<?php

use yii\db\Migration;

/**
 * Class m230217_010301_add_constraint_in_general_ledger_table
 */
class m230217_010301_add_constraint_in_general_ledger_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-gen-led-book_id', 'general_ledger', 'book_id');
        $this->createIndex('idx-gen-led-object_code', 'general_ledger', 'object_code');
        $this->addForeignKey('fk-gen-led-book_id', 'general_ledger', 'book_id', 'books', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-gen-led-book_id', 'general_ledger');
        $this->dropIndex('idx-gen-led-book_id', 'general_ledger');
        $this->dropIndex('idx-gen-led-object_code', 'general_ledger');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230217_010301_add_constraint_in_general_ledger_table cannot be reverted.\n";

        return false;
    }
    */
}
