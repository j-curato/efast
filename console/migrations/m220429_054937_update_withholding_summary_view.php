<?php

use yii\db\Migration;

/**
 * Class m220429_054937_update_withholding_summary_view
 */
class m220429_054937_update_withholding_summary_view extends Migration
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
                dv_accounting_entries.id as payroll_item_id,
                payroll.payroll_number,
                process_ors.serial_number as ors_number,
                dv_aucs.dv_number,
                payee.account_name as payee,
                payee.id as payee_id,
                dv_accounting_entries.object_code,
                accounting_codes.account_title,
                IFNULL(dv_accounting_entries.credit,0) + IFNULL(dv_accounting_entries.debit,0) as amount,
                remitted.remitted_amount,
                (IFNULL(dv_accounting_entries.credit,0) + IFNULL(dv_accounting_entries.debit,0)) - IFNULL(  remitted.remitted_amount,0) unremitted_amount

                FROM payroll
                INNER JOIN dv_aucs ON payroll.id = dv_aucs.payroll_id
                INNER  JOIN dv_accounting_entries ON payroll.id = dv_accounting_entries.payroll_id
                INNER JOIN process_ors ON payroll.process_ors_id = process_ors.id
                LEFT JOIN accounting_codes ON dv_accounting_entries.object_code =  accounting_codes.object_code 
                INNER JOIN remittance_payee ON dv_accounting_entries.remittance_payee_id = remittance_payee.id
                INNER JOIN payee ON remittance_payee.payee_id = payee.id 
                LEFT JOIN (SELECT 
                remittance_items.fk_dv_acounting_entries_id,
                IFNULL(SUM(remittance_items.amount),0)as remitted_amount
                FROM remittance_items
                WHERE remittance_items.is_removed =0
                GROUP BY fk_dv_acounting_entries_id) as remitted ON dv_accounting_entries.id = remitted.fk_dv_acounting_entries_id
        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220429_054937_update_withholding_summary_view cannot be reverted.\n";

        return false;
    }
    */
}
