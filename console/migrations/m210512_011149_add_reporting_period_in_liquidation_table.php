<?php

use yii\db\Migration;

/**
 * Class m210512_011149_add_reporting_period_in_liquidation_table
 */
class m210512_011149_add_reporting_period_in_liquidation_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
$this->addColumn('liquidation','reporting_period',$this->string(20));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation','reporting_period');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210512_011149_add_reporting_period_in_liquidation_table cannot be reverted.\n";

        return false;
    }
    */
}
