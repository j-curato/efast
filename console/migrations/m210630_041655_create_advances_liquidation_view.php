<?php

use yii\db\Migration;

/**
 * Class m210630_041655_create_advances_liquidation_view
 */
class m210630_041655_create_advances_liquidation_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //         Yii::$app->db->createCommand("CREATE VIEW advances_liquidation AS 
        //        SELECT
        // DISTINCT
        // liquidation.province,
        // liquidation.check_date,
        // liquidation.check_number,
        // liquidation.is_cancelled,
        // liquidation.dv_number,
        // liquidation_entries.reporting_period,
        // advances_entries.fund_source,
        // liquidation.payee,
        // CONCAT(liquidation.particular,' - ',advances.province) as particular,
        // chart_of_accounts.uacs as gl_object_code,
        // chart_of_accounts.general_ledger as gl_account_title,
        // 0 as amount,
        // liquidation_entries.withdrawals,
        // liquidation_entries.vat_nonvat,
        // liquidation_entries.expanded_tax,
        // advances_entries.report_type,
        // advances_entries.advances_type,
        // sub_accounts_view.object_code as sl_object_code,
        // sub_accounts_view.account_title as sl_account_title,
        // books.`name` as book_name


        // FROM liquidation_entries
        // LEFT JOIN liquidation ON liquidation_entries.liquidation_id= liquidation.id
        // LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id =advances_entries.id
        // LEFT JOIN advances ON advances_entries.advances_id=advances.id
        // LEFT JOIN chart_of_accounts ON liquidation_entries.chart_of_account_id = chart_of_accounts.id
        // LEFT JOIN sub_accounts_view ON advances_entries.object_code = sub_accounts_view.object_code
        // LEFT JOIN payee ON liquidation.payee_id=payee.id
        // LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id = cash_disbursement.id
        // LEFT JOIN books ON  cash_disbursement.book_id=books.id

        //  UNION ALL

        // SELECT 
        // advances.province,
        // cash_disbursement.issuance_date,
        // cash_disbursement.check_or_ada_no,
        // cash_disbursement.is_cancelled,
        // dv_aucs.dv_number,
        // advances_entries.reporting_period,
        // advances_entries.fund_source ,
        // payee.account_name as payee,
        // dv_aucs.particular,
        // '' as gl_object_code,
        // '' as gl_account_title,
        // advances_entries.amount,
        // 0 as withdrawals,
        // 0 as vat_nonvat,
        // 0 as expanded_tax,
        // advances_entries.report_type,
        // advances_entries.advances_type,
        // sub_accounts_view.object_code as sl_object_code,
        // sub_accounts_view.account_title as sl_account_title,
        // books.`name` as book_name

        // FROM advances_entries
        // LEFT JOIN advances ON advances_entries.advances_id=advances.id
        // LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id=cash_disbursement.id
        // LEFT JOIN sub_accounts_view ON advances_entries.object_code = sub_accounts_view.object_code
        // LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
        // LEFT JOIN payee ON dv_aucs.payee_id=payee.id
        // LEFT JOIN books ON cash_disbursement.book_id =books.id ")->query();
        // for migration
        Yii::$app->db->createCommand(" DROP VIEW IF EXISTS advances_liquidation;
        CREATE VIEW advances_liquidation AS 
       SELECT
DISTINCT
liquidation.check_date,
liquidation.check_number,
liquidation.is_cancelled,
liquidation.dv_number,
liquidation_entries.reporting_period,
advances_entries.fund_source,
liquidation.payee,
CONCAT(liquidation.particular,' - ',advances.province) as particular,
chart_of_accounts.uacs as gl_object_code,
chart_of_accounts.general_ledger as gl_account_title,
0 as amount,
liquidation_entries.withdrawals,
liquidation_entries.vat_nonvat,
liquidation_entries.expanded_tax,
advances_entries.report_type,
advances_entries.advances_type,
sub_accounts_view.object_code as sl_object_code,
sub_accounts_view.account_title as sl_account_title,
books.`name` as book_name


FROM liquidation_entries
LEFT JOIN liquidation ON liquidation_entries.liquidation_id= liquidation.id
LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id =advances_entries.id
LEFT JOIN advances ON advances_entries.advances_id=advances.id
LEFT JOIN chart_of_accounts ON liquidation_entries.chart_of_account_id = chart_of_accounts.id
LEFT JOIN sub_accounts_view ON advances_entries.object_code = sub_accounts_view.object_code
LEFT JOIN payee ON liquidation.payee_id=payee.id
LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id = cash_disbursement.id
LEFT JOIN books ON  cash_disbursement.book_id=books.id

 UNION ALL

SELECT 
advances.province,
cash_disbursement.issuance_date,
cash_disbursement.check_or_ada_no,
cash_disbursement.is_cancelled,
dv_aucs.dv_number,
advances_entries.reporting_period,
advances_entries.fund_source ,
payee.account_name as payee,
dv_aucs.particular,
'' as gl_object_code,
'' as gl_account_title,
advances_entries.amount,
0 as withdrawals,
0 as vat_nonvat,
0 as expanded_tax,
advances_entries.report_type,
advances_entries.advances_type,
sub_accounts_view.object_code as sl_object_code,
sub_accounts_view.account_title as sl_account_title,
books.`name` as book_name

FROM advances_entries
LEFT JOIN advances ON advances_entries.advances_id=advances.id
LEFT JOIN cash_disbursement ON advances_entries.cash_disbursement_id=cash_disbursement.id
LEFT JOIN sub_accounts_view ON advances_entries.object_code = sub_accounts_view.object_code
LEFT JOIN dv_aucs ON cash_disbursement.dv_aucs_id = dv_aucs.id
LEFT JOIN payee ON dv_aucs.payee_id=payee.id
LEFT JOIN books ON cash_disbursement.book_id =books.id ")->query();
    }



    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP VIEW advances_liquidation")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210630_041655_create_advances_liquidation_view cannot be reverted.\n";

        return false;
    }
    */
}
