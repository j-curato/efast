<?php

use yii\db\Migration;

/**
 * Class m210530_030524_add_book_id_in_advances_table
 */
class m210530_030524_add_book_id_in_advances_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advances','book_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('advances','book_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210530_030524_add_book_id_in_advances_table cannot be reverted.\n";

        return false;
    }
    */
}
