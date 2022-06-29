<?php

use yii\db\Migration;

/**
 * Class m220616_063928_add_is_reimburse_in_ro_liquidation_report_items_table
 */
class m220616_063928_add_is_reimburse_in_ro_liquidation_report_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('ro_liquidation_report_items','is_reimburse',$this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('ro_liquidation_report_items','is_reimburse',$this->boolean()->defaultValue(false));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220616_063928_add_is_reimburse_in_ro_liquidation_report_items_table cannot be reverted.\n";

        return false;
    }
    */
}
