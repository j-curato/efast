<?php

use yii\db\Migration;

/**
 * Class m210714_034001_create_transaction_archive_view
 */
class m210714_034001_create_transaction_archive_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand('CREATE VIEW transaction_archive as 
        SELECT 
        payee.account_name,
        `transaction`.tracking_number,
        `transaction`.gross_amount,
        process_ors.serial_number as ors_number,
        total_ors.total_obligation,
        dv_aucs.dv_number,
        cash_disbursement.check_or_ada_no,
        cash_disbursement.ada_number,
        dv_aucs_entries.amount_disbursed,
        dv_aucs_entries.vat_nonvat,
        dv_aucs_entries.ewt_goods_services,
        dv_aucs_entries.compensation,
        dv_aucs_entries.other_trust_liabilities

        FROM dv_aucs
        LEFT JOIN dv_aucs_entries ON dv_aucs.id = dv_aucs_entries.dv_aucs_id
        INNER JOIN cash_disbursement ON dv_aucs.id = cash_disbursement.dv_aucs_id
        INNER JOIN process_ors ON dv_aucs_entries.process_ors_id = process_ors.id

        LEFT JOIN `transaction` ON process_ors.transaction_id = `transaction`.id
        LEFT JOIN payee ON dv_aucs.payee_id = payee.id
        LEFT JOIN (
        SELECT 
        SUM(raoud_entries.amount) as total_obligation,
        raouds.process_ors_id
        FROM raouds
        LEFT JOIN raoud_entries ON raouds.id = raoud_entries.raoud_id
        GROUP BY raouds.process_ors_id
        ) as total_ors ON process_ors.id = total_ors.process_ors_id

        WHERE 
        cash_disbursement.is_cancelled=0
        AND dv_aucs.is_cancelled=0
        AND `transaction`.tracking_number IN 
        (SELECT transaction_totals.tracking_number
        FROM transaction_totals
        where 
        transaction_totals.total_dv >=transaction_totals.total_ors 
        AND transaction_totals.total_ors>0)
        ORDER BY `transaction`.id
        ')->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand('DROP VIEW transaction_archive')->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210714_034001_create_transaction_archive_view cannot be reverted.\n";

        return false;
    }
    */
}
