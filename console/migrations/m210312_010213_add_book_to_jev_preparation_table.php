<?php

use yii\db\Migration;

/**
 * Class m210312_010213_add_book_to_jev_preparation_table
 */
class m210312_010213_add_book_to_jev_preparation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        //  NOT NULL
        $this->addColumn('jev_preparation','book_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('jev_preparation','book_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210312_010213_add_book_to_jev_preparation_table cannot be reverted.\n";

        return false;
    }
    */
}
