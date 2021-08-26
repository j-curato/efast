<?php

use yii\db\Migration;

/**
 * Class m210824_003929_add_new_chart_of_account_id_in_liquidation_entries_table
 */
class m210824_003929_add_new_chart_of_account_id_in_liquidation_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('liquidation_entries','new_chart_of_account_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('liquidation_entries','new_chart_of_account_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210824_003929_add_new_chart_of_account_id_in_liquidation_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
