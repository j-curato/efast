<?php

use yii\db\Migration;

/**
 * Class m211022_014355_add_begin_balance_in_check_range_table
 */
class m211022_014355_add_begin_balance_in_check_range_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('check_range', 'begin_balance', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('check_range', 'begin_balance');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211022_014355_add_begin_balance_in_check_range_table cannot be reverted.\n";

        return false;
    }
    */
}
