<?php

use yii\db\Migration;

/**
 * Class m210421_095151_add_account_number_to_cash_recieved_table
 */
class m210421_095151_add_account_number_to_cash_recieved_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cash_recieved','account_number',$this->string(100));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('cash_recieved','account_number');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210421_095151_add_account_number_to_cash_recieved_table cannot be reverted.\n";

        return false;
    }
    */
}
