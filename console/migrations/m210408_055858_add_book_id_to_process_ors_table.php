<?php

use yii\db\Migration;

/**
 * Class m210408_055858_add_book_id_to_process_ors_table
 */
class m210408_055858_add_book_id_to_process_ors_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('process_ors','book_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('process_ors','book_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210408_055858_add_book_id_to_process_ors_table cannot be reverted.\n";

        return false;
    }
    */
}
