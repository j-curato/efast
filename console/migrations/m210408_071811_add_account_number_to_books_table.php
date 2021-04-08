<?php

use yii\db\Migration;

/**
 * Class m210408_071811_add_account_number_to_books_table
 */
class m210408_071811_add_account_number_to_books_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('books','account_number',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('books','account_number');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210408_071811_add_account_number_to_books_table cannot be reverted.\n";

        return false;
    }
    */
}
