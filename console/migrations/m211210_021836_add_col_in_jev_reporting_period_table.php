<?php

use yii\db\Migration;

/**
 * Class m211210_021836_add_col_in_jev_reporting_period_table
 */
class m211210_021836_add_col_in_jev_reporting_period_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('jev_reporting_period','book_id',$this->integer());
        $this->addColumn('jev_reporting_period','reference',$this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('jev_reporting_period','book_id');
        $this->dropColumn('jev_reporting_period','reference');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211210_021836_add_col_in_jev_reporting_period_table cannot be reverted.\n";

        return false;
    }
    */
}
