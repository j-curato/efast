<?php

use yii\db\Migration;

/**
 * Class m211021_070555_add_cancel_reporting_period_in_liquidation_table
 */
class m211021_070555_add_cancel_reporting_period_in_liquidation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation','cancel_reporting_period',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation','cancel_reporting_period');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211021_070555_add_cancel_reporting_period_in_liquidation_table cannot be reverted.\n";

        return false;
    }
    */
}
