<?php

use yii\db\Migration;

/**
 * Class m230217_005237_add_constraint_in_cash_adjustment_table
 */
class m230217_005237_add_constraint_in_cash_adjustment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-csh-adj-book_id', 'cash_adjustment', 'book_id');
        $this->addForeignKey('fk-csh-adj-book_id', 'cash_adjustment', 'book_id', 'books', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-csh-adj-book_id', 'cash_adjustment');
        $this->dropIndex('idx-csh-adj-book_id', 'cash_adjustment');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230217_005237_add_constraint_in_cash_adjustment_table cannot be reverted.\n";

        return false;
    }
    */
}
