<?php

use yii\db\Migration;

/**
 * Class m210530_020230_add_reporting_period_in_advances_table
 */
class m210530_020230_add_reporting_period_in_advances_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
$this->addColumn('advances','reporting_period',$this->string(20));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('advances','reporting_period');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210530_020230_add_reporting_period_in_advances_table cannot be reverted.\n";

        return false;
    }
    */
}
