<?php

use yii\db\Migration;

/**
 * Class m230510_022355_add_columns_in_books_table
 */
class m230510_022355_add_columns_in_books_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('books', 'fk_bank_id', $this->integer());
        $this->createIndex('idx-bks-fk_bank_id', 'books', 'fk_bank_id');
        $this->addForeignKey('fk-bks-fk_bank_id', 'books', 'fk_bank_id', 'banks', 'id', 'RESTRICT');
        $this->addColumn('books', 'account_name', $this->string());
        $this->addColumn('books', 'funding_source_code', $this->integer());
        $this->addColumn('books', 'lapsing', $this->string());
        $this->addColumn('books', 'remarks', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-bks-fk_bank_id', 'books');
        $this->dropIndex('idx-bks-fk_bank_id', 'books');
        $this->dropColumn('books', 'fk_bank_id', $this->integer());
        $this->dropColumn('books', 'account_name', $this->string());
        $this->dropColumn('books', 'funding_source_code', $this->integer());
        $this->dropColumn('books', 'lapsing', $this->string());
        $this->dropColumn('books', 'remarks', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230510_022355_add_columns_in_books_table cannot be reverted.\n";

        return false;
    }
    */
}
