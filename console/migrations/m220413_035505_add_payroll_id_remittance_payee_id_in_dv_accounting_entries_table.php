<?php

use yii\db\Migration;

/**
 * Class m220413_035505_add_payroll_id_remittance_payee_id_in_dv_accounting_entries_table
 */
class m220413_035505_add_payroll_id_remittance_payee_id_in_dv_accounting_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dv_accounting_entries','payroll_id',$this->bigInteger());
        $this->addColumn('dv_accounting_entries','remittance_payee_id',$this->bigInteger());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('dv_accounting_entries','payroll_id');
        $this->dropColumn('dv_accounting_entries','remittance_payee_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220413_035505_add_payroll_id_remittance_payee_id_in_dv_accounting_entries_table cannot be reverted.\n";

        return false;
    }
    */
}
