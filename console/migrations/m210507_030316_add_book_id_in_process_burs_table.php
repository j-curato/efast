<?php

use yii\db\Migration;

/**
 * Class m210507_030316_add_book_id_in_process_burs_table
 */
class m210507_030316_add_book_id_in_process_burs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('process_burs', 'book_id', $this->integer());
        $this->addColumn('process_burs', 'date', $this->string(30));
        $this->addColumn('process_burs', 'is_cancelled', $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('process_burs', 'book_id');
        $this->dropColumn('process_burs', 'date');
        $this->dropColumn('process_burs', 'is_cancelled');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210507_030316_add_book_id_in_process_burs_table cannot be reverted.\n";

        return false;
    }
    */
}
