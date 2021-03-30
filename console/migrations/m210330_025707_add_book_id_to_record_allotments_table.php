<?php

use yii\db\Migration;

/**
 * Class m210330_025707_add_book_id_to_record_allotments_table
 */
class m210330_025707_add_book_id_to_record_allotments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('record_allotments','book_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('record_allotments','book_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210330_025707_add_book_id_to_record_allotments_table cannot be reverted.\n";

        return false;
    }
    */
}
