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
            SELECT Row_number() OVER(ORDER BY object_code DESC) AS 'row_number',q.* FROM 
(
SELECT 
advances.nft_number,
dv_aucs.dv_number,
cash_disbursement.mode_of_payment,
cash_disbursement.check_or_ada_no as check_number,
cash_disbursement.issuance_date,
payee.account_name as payee,
dv_aucs.particular,
advances_entries.amount,
books.`name`as book_name,
advances.province,
advances.reporting_period,
advances.particular as fund_source,
advances.report_type,
sub_accounts_view.object_code,
sub_accounts_view.account_title

FROM `advances_entries`,advances,sub_accounts_view,cash_disbursement,dv_aucs,payee,books
WHERE advances_entries.advances_id = advances.id
AND advances_entries.object_code = sub_accounts_view.object_code
AND advances_entries.cash_disbursement_id= cash_disbursement.id
AND cash_disbursement.dv_aucs_id = dv_aucs.id
AND dv_aucs.payee_id=payee.id
AND cash_disbursement.book_id=books.id 
) AS q 
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
