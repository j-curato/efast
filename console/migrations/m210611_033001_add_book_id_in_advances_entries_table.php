<?php

use yii\db\Migration;

/**
 * Class m210611_033001_add_book_id_in_advances_entries_table
 */
class m210611_033001_add_book_id_in_advances_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('advances_entries','book_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('advances_entries','book_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210611_033001_add_book_id_in_advances_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
