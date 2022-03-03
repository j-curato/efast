<?php

use yii\db\Migration;

/**
 * Class m220303_021644_add_book_typ_in_books_table
 */
class m220303_021644_add_book_typ_in_books_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('books','type',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('books','type');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220303_021644_add_book_typ_in_books_table cannot be reverted.\n";

        return false;
    }
    */
}
