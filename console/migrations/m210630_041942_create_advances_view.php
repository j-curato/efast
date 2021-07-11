<?php

use yii\db\Migration;

/**
 * Class m210630_041942_create_advances_view
 */
class m210630_041942_create_advances_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("CREATE VIEW advances_view AS 
           SELECT 
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
advances.report_type,
sub_accounts_view.object_code,
sub_accounts_view.account_title,
cash_disbursement.book_id


FROM `advances_entries`
LEFT JOIN(SELECT SUM(liquidation_entries.withdrawals)as total_liquidation,
liquidation_entries.advances_entries_id
FROM liquidation_entries GROUP BY liquidation_entries.advances_entries_id) as liq
ON advances_entries.id = liq.advances_entries_id
,advances,sub_accounts_view,cash_disbursement,dv_aucs,payee,books,
dv_aucs_entries
LEFT JOIN process_ors ON dv_aucs_entries.process_ors_id = process_ors.id 
LEFT JOIN `transaction` on process_ors.transaction_id = `transaction`.id
LEFT JOIN responsibility_center ON `transaction`.responsibility_center_id= responsibility_center.id

WHERE advances_entries.advances_id = advances.id
AND advances_entries.object_code = sub_accounts_view.object_code
AND advances_entries.cash_disbursement_id= cash_disbursement.id
AND cash_disbursement.dv_aucs_id = dv_aucs.id
AND dv_aucs.payee_id=payee.id
AND cash_disbursement.book_id=books.id 
AND dv_aucs.id = dv_aucs_entries.dv_aucs_id 
        ")->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP VIEW advances_view ")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210630_041942_create_advances_view cannot be reverted.\n";

        return false;
    }
    */
}
