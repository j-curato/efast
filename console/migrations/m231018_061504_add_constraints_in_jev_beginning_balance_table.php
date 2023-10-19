<?php

use yii\db\Migration;

/**
 * Class m231018_061504_add_constraints_in_jev_beginning_balance_table
 */
class m231018_061504_add_constraints_in_jev_beginning_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-jev_beginning_balance-book_id', 'jev_beginning_balance', 'book_id');
        $this->addForeignKey('fk-jev_beginning_balance-book_id', 'jev_beginning_balance', 'book_id', 'books', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-jev_beginning_balance-book_id', 'jev_beginning_balance');
        $this->dropIndex('idx-jev_beginning_balance-book_id', 'jev_beginning_balance');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231018_061504_add_constraints_in_jev_beginning_balance_table cannot be reverted.\n";

        return false;
    }
    */
}
