<?php

use yii\db\Migration;

/**
 * Class m210708_090624_add_date_particular_book_id_sl_in_other_reciepts_table
 */
class m210708_090624_add_date_particular_book_id_sl_in_other_reciepts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('other_reciepts', 'date', $this->date());
        $this->addColumn('other_reciepts', 'particular', $this->text());
        $this->addColumn('other_reciepts', 'book_id', $this->integer());
        $this->addColumn('other_reciepts', 'sl_object_code', $this->string());
        $this->addColumn('other_reciepts', 'amount', $this->decimal(10, 2));
        $this->addColumn('other_reciepts', 'created_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('other_reciepts', 'date');
        $this->dropColumn('other_reciepts', 'particular');
        $this->dropColumn('other_reciepts', 'book_id');
        $this->dropColumn('other_reciepts', 'sl_object_code');
        $this->dropColumn('other_reciepts', 'amount');
        $this->dropColumn('other_reciepts', 'created_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210708_090624_add_date_particular_book_id_sl_in_other_reciepts_table cannot be reverted.\n";

        return false;
    }
    */
}
