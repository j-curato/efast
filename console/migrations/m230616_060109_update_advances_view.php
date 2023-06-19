<?php

use yii\db\Migration;

/**
 * Class m230616_060109_update_advances_view
 */
class m230616_060109_update_advances_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS advances_view;
        CREATE VIEW advances_view as 
        WITH cte_gd_checks as (
            SELECT 
            cash_disbursement_items.fk_dv_aucs_id,
            cash_disbursement.check_or_ada_no as check_number,
            cash_disbursement.ada_number,
            cash_disbursement.issuance_date as check_date,
            mode_of_payments.`name` as mode_of_payment,
            books.`name` as book_name
            FROM 
            cash_disbursement
            JOIN cash_disbursement_items ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
            LEFT JOIN mode_of_payments ON cash_disbursement.fk_mode_of_payment_id = mode_of_payments.id
            LEFT JOIN books ON cash_disbursement.book_id = books.id
            WHERE 
            cash_disbursement_items.is_deleted = 0
            AND cash_disbursement.is_cancelled = 0
            AND NOT EXISTS (SELECT * FROM cash_disbursement c WHERE c.is_cancelled = 1 AND c.parent_disbursement = cash_disbursement.id)
            ),
             cte_adv_obj_codes as (
            SELECT advances_entries.object_code
            FROM advances_entries
            GROUP BY advances_entries.object_code
            ),
            sub_accounts as (
            SELECT sub_accounts2.object_code,sub_accounts2.`name` as account_title FROM sub_accounts2 WHERE EXISTS (SELECT * FROM cte_adv_obj_codes c WHERE c.object_code = sub_accounts2.object_code)
            UNION
            SELECT sub_accounts1.object_code,sub_accounts1.`name` FROM sub_accounts1 WHERE EXISTS (SELECT * FROM cte_adv_obj_codes c WHERE c.object_code = sub_accounts1.object_code)
            
            ) 
            SELECT
            advances.id,
            advances_entries.id as entry_id,
            office.office_name as province,
            advances.reporting_period,
            advances.nft_number,
             CONCAT(bank_account.account_number,'-',bank_account.account_name) as bank_account,
            bank_account.account_name,
            bank_account.account_number,
            advances_entries.fund_source,
            advances_entries.amount,
            liq.total_liquidation,
            COALESCE(advances_entries.amount,0)-COALESCE(liq.total_liquidation,0) as balance,
            dv_aucs.dv_number,
            payee.account_name as payee,
            dv_aucs.particular,
            
            advances_report_types.`name` report_type,
            cte_gd_checks.check_number,
            cte_gd_checks.ada_number,
            cte_gd_checks.check_date,
            cte_gd_checks.mode_of_payment,
            cte_gd_checks.book_name,
            fund_source_type.`name` as fund_source_type,
            advances_entries.object_code,
            sub_accounts.account_title
            FROM advances
            JOIN dv_aucs ON advances.dv_aucs_id = dv_aucs.id
            JOIN advances_entries ON advances.id = advances_entries.advances_id
            LEFT JOIN bank_account ON advances.bank_account_id = bank_account.id
            LEFT JOIN office ON bank_account.fk_office_id = office.id
             LEFT JOIN(SELECT SUM(liquidation_entries.withdrawals)as total_liquidation,
            liquidation_entries.advances_entries_id
            FROM liquidation_entries GROUP BY liquidation_entries.advances_entries_id) as liq
            ON advances_entries.id = liq.advances_entries_id
            LEFT JOIN payee ON dv_aucs.payee_id = payee.id
            LEFT JOIN cte_gd_checks ON dv_aucs.id = cte_gd_checks.fk_dv_aucs_id
            LEFT JOIN advances_report_types ON advances_entries.fk_advances_report_type_id = advances_report_types.id
            LEFT JOIN fund_source_type ON advances_entries.fk_fund_source_type_id = fund_source_type.id
            LEFT JOIN  sub_accounts ON advances_entries.object_code = sub_accounts.object_code
            WHERE advances_entries.is_deleted NOT IN (1,9)
            ORDER BY  cte_gd_checks.check_date DESC 
            
            
            
            
            
            
            
            
            
        ")->query();
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
        echo "m230616_060109_update_advances_view cannot be reverted.\n";

        return false;
    }
    */
}
