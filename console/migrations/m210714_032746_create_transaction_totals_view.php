<?php

use yii\db\Migration;

/**
 * Class m210714_032746_create_transaction_totals_view
 */
class m210714_032746_create_transaction_totals_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("
        DROP VIEW IF EXISTS transaction_totals;
        CREATE VIEW transaction_totals as 
        SELECT 
        `transaction`.id,
	    `transaction`.created_at,
        `transaction`.tracking_number,
		`transaction`.payroll_number,
        responsibility_center.`name` as r_center_name,
        payee.account_name as payee,
        `transaction`.particular,
        `transaction`.gross_amount,
        COALESCE(totals.total_ors,0) as total_ors,
        COALESCE(totals.total_dv,0) as total_dv

        FROM `transaction`
        LEFT JOIN payee ON `transaction`.payee_id = payee.id
        LEFT JOIN responsibility_center ON `transaction`.responsibility_center_id = responsibility_center.id
        LEFT JOIN
        (
        SELECT 

        SUM(ors.total_ors) as total_ors,
        SUM(dv.total_dv_per_ors) total_dv,
        process_ors.transaction_id

        FROM process_ors

        LEFT JOIN (
        SELECT 
        SUM(raoud_entries.amount) as total_ors,
        process_ors.id
        FROM process_ors
        LEFT JOIN raouds ON process_ors.id = raouds.process_ors_id
        LEFT JOIN raoud_entries ON raouds.id = raoud_entries.raoud_id
        GROUP BY process_ors.id
        ) as ors ON process_ors.id =ors.id
        LEFT JOIN (

        SELECT
        SUM(dv_aucs_entries.amount_disbursed)
        + SUM(dv_aucs_entries.vat_nonvat)
        +SUM(dv_aucs_entries.ewt_goods_services)
        +SUM(dv_aucs_entries.compensation)
        +SUM(dv_aucs_entries.other_trust_liabilities)
        as total_dv_per_ors,
        dv_aucs_entries.process_ors_id

        FROM dv_aucs
        INNER JOIN cash_disbursement ON dv_aucs.id = cash_disbursement.dv_aucs_id
        LEFT JOIN dv_aucs_entries on dv_aucs.id = dv_aucs_entries.dv_aucs_id
        WHERE
        cash_disbursement.is_cancelled = 0
        AND dv_aucs.is_cancelled=0
        GROUP BY dv_aucs_entries.process_ors_id
        ORDER BY dv_aucs_entries.process_ors_id

        ) as dv ON process_ors.id = dv.process_ors_id
        WHERE process_ors.is_cancelled = 0
        GROUP BY process_ors.transaction_id
        ORDER BY process_ors.transaction_id
        ) as totals ON `transaction`.id = totals.transaction_id


        ")->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP VIEW transaction_totals")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210714_032746_create_transaction_totals_view cannot be reverted.\n";

        return false;
    }
    */
}
