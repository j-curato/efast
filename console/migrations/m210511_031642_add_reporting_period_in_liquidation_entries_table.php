<?php

use yii\db\Migration;

/**
 * Class m210511_031642_add_reporting_period_in_liquidation_entries_table
 */
class m210511_031642_add_reporting_period_in_liquidation_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation_entries','reporting_period',$this->string(20));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation_entries','reporting_period');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210511_031642_add_reporting_period_in_liquidation_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
