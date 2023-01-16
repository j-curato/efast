<?php

use yii\db\Migration;

/**
 * Class m230116_085029_add_fk_book_id_in_transaction_table
 */
class m230116_085029_add_fk_book_id_in_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('transaction', 'fk_book_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('transaction', 'fk_book_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230116_085029_add_fk_book_id_in_transaction_table cannot be reverted.\n";

        return false;
    }
    */
}
