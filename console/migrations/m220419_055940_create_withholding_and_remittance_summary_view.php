<?php

use yii\db\Migration;

/**
 * Class m220419_055940_create_withholding_and_remittance_summary_view
 */
class m220419_055940_create_withholding_and_remittance_summary_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
         
            DROP VIEW IF EXISTS withholding_and_remittance_summary; 
            CREATE VIEW withholding_and_remittance_summary as 
            SELECT 
            payroll.payroll_number,
            process_ors.serial_number as ors_number,
            dv_aucs.dv_number,
            dv_accounting_entries.object_code,
            accounting_codes.account_title,
            dv_accounting_entries.credit + dv_accounting_entries.debit as amount
            FROM payroll
            LEFT JOIN dv_aucs ON payroll.id = dv_aucs.payroll_id
            LEFT JOIN dv_accounting_entries ON payroll.id = dv_accounting_entries.payroll_id
            LEFT JOIN process_ors ON payroll.process_ors_id = process_ors.id
            LEFT JOIN accounting_codes ON dv_accounting_entries.object_code =  accounting_codes.object_code


        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        YIi::$app->db->createCommand("DROP VIEW IF EXISTS withholding_and_remittance_summary")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220419_055940_create_withholding_and_remittance_summary_view cannot be reverted.\n";

        return false;
    }
    */
}
