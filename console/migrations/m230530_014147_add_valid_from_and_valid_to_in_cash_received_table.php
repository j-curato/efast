<?php

use yii\db\Migration;

/**
 * Class m230530_014147_add_valid_from_and_valid_to_in_cash_received_table
 */
class m230530_014147_add_valid_from_and_valid_to_in_cash_received_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cash_received', 'valid_from', $this->date());
        $this->addColumn('cash_received', 'valid_to', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('cash_received', 'valid_from');
        $this->dropColumn('cash_received', 'valid_to');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230530_014147_add_valid_from_and_valid_to_in_cash_received_table cannot be reverted.\n";

        return false;
    }
    */
}
