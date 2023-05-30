<?php

use yii\db\Migration;

/**
 * Class m230530_011637_rename_cash_recieve_table
 */
class m230530_011637_rename_cash_recieve_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('cash_received', 'cash_received');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('cash_received', 'cash_received');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230530_011637_rename_cash_recieve_table cannot be reverted.\n";

        return false;
    }
    */
}
