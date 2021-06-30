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
        Yii::$app->db->createCommand("CREATE VIEW advances_liquidation AS 
        SELECT
advances.province,
liquidation.check_date,
liquidation.check_number,
liquidation.is_cancelled,
liquidation.dv_number,
liquidation_entries.reporting_period,
advances_entries.fund_source,

payee.account_name as payee,
liquidation.particular,
chart_of_accounts.uacs as gl_object_code,
chart_of_accounts.general_ledger as gl_account_title,
'' as amount,
liquidation_entries.withdrawals,
liquidation_entries.vat_nonvat,
liquidation_entries.expanded_tax,
advances.report_type,
sub_accounts_view.object_code as sl_object_code,
sub_accounts_view.account_title as sl_account_title,
books.`name` as book_name


FROM liquidation_entries,liquidation,advances_entries,advances,chart_of_accounts,sub_accounts_view,payee,cash_disbursement,books
where liquidation_entries.advances_entries_id = advances_entries.id
AND liquidation_entries.liquidation_id = liquidation.id
AND advances_entries.advances_id=advances.id
AND liquidation_entries.chart_of_account_id=chart_of_accounts.id
AND advances_entries.object_code = sub_accounts_view.object_code
AND liquidation.payee_id=payee.id
AND advances_entries.cash_disbursement_id = cash_disbursement.id
AND cash_disbursement.book_id=books.id

 UNION

SELECT 
advances.province,
cash_disbursement.issuance_date,
cash_disbursement.check_or_ada_no,
cash_disbursement.is_cancelled,
dv_aucs.dv_number,
cash_disbursement.reporting_period,
advances_entries.fund_source ,
payee.account_name as payee,
dv_aucs.particular,
'' as gl_object_code,
'' as gl_account_title,
advances_entries.amount,
'' as withdrawals,
'' as vat_nonvat,
'' as expanded_tax,
advances.report_type,
sub_accounts_view.object_code as sl_object_code,
sub_accounts_view.account_title as sl_account_title,
books.`name` as book_name

FROM advances_entries,advances,cash_disbursement,sub_accounts_view,payee,dv_aucs,books
where advances_entries.advances_id=advances.id
AND advances_entries.object_code = sub_accounts_view.object_code
AND advances_entries.cash_disbursement_id=cash_disbursement.id
AND cash_disbursement.dv_aucs_id = dv_aucs.id
AND dv_aucs.payee_id=payee.id
AND cash_disbursement.book_id =books.id ")->query();
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
