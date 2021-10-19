<?php

use yii\db\Migration;

/**
 * Class m211019_033514_add_reporting_period_in_check_range_table
 */
class m211019_033514_add_reporting_period_in_check_range_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('check_range', 'reporting_period',$this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('check_range', 'reporting_period');
    }

    /*
    // Use up()/down() to run migration code without a transaction.#
    public function up()
    {

    }

    public function down()
    {
        echo "m211019_033514_add_reporting_period_in_check_range_table cannot be reverted.\n";

        return false;
    }
    */
}
