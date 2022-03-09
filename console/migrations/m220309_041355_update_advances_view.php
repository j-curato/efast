<?php

use yii\db\Migration;

/**
 * Class m220309_041355_update_advances_view
 */
class m220309_041355_update_advances_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS advances_view;
        CREATE VIEW advances_view AS SELECT 
        advances_entries.id as entry_id,
        advances_entries.advances_id,
        advances.nft_number,
        dv_aucs.dv_number,
        responsibility_center.`name` as r_center_name,
        cash_disbursement.mode_of_payment,
        cash_disbursement.check_or_ada_no as check_number,
        cash_disbursement.issuance_date as check_date,
        payee.account_name as payee,
        dv_aucs.particular,
        advances_entries.amount,
        liq.total_liquidation,
        books.`name`as book_name,
        advances.province,
        advances.reporting_period,
        advances_entries.fund_source,
        advances_entries.fund_source_type,
        advances_entries.report_type,
        advances_entries.advances_type,
        sub_accounts_view.object_code,
        sub_accounts_view.account_title,
        cash_disbursement.book_id,
        CONCAT(bank_account.account_number,'-',bank_account.account_name) as bank_account,
        advances_entries.is_deleted
        
        
        
        FROM `advances_entries`
        LEFT JOIN(SELECT SUM(liquidation_entries.withdrawals)as total_liquidation,
        liquidation_entries.advances_entries_id
        FROM liquidation_entries GROUP BY liquidation_entries.advances_entries_id) as liq
        ON advances_entries.id = liq.advances_entries_id
        LEFT JOIN advances ON advances_entries.advances_id = advances.id
        LEFT JOIN sub_accounts_view ON advances_entries.object_code = sub_accounts_view.object_code
        LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id= cash_disbursement.id
        LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
        LEFT JOIN payee ON dv_aucs.payee_id=payee.id
        LEFT JOIN books ON  cash_disbursement.book_id=books.id 
        LEFT JOIN dv_aucs_entries ON dv_aucs.id = dv_aucs_entries.dv_aucs_id 
        LEFT JOIN process_ors ON dv_aucs_entries.process_ors_id = process_ors.id 
        LEFT JOIN `transaction` on process_ors.transaction_id = `transaction`.id
        LEFT JOIN responsibility_center ON `transaction`.responsibility_center_id= responsibility_center.id
        LEFT JOIN bank_account ON advances.bank_account_id = bank_account.id
        WHERE advances_entries.is_deleted != 1
        ORDER BY  advances.nft_number DESC ")->query();
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
        echo "m220309_041355_update_advances_view cannot be reverted.\n";

        return false;
    }
    */
}
