<?php

use yii\db\Migration;

/**
 * Class m220308_060150_drop_detailed_dv_aucs_view
 */
class m220308_060150_drop_detailed_dv_aucs_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS detailed_dv_aucs")->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $sql = <<<SQL
        DROP VIEW IF EXISTS detailed_dv_aucs;
        CREATE VIEW detailed_dv_aucs as 
        SELECT 
            dv_aucs.dv_number,
            dv_aucs.reporting_period,
            process_ors.serial_number as obligation_number,
            `transaction`.tracking_number as transaction_tracking_number,
            payee.account_name as payee,
            dv_aucs.particular,
            dv_aucs_entries.amount_disbursed as total_dv,
            dv_aucs_entries.vat_nonvat as total_vat,
            dv_aucs_entries.ewt_goods_services as total_ewt,
            dv_aucs_entries.compensation as total_compensation,
            mfo_pap_code.`code` as mfo_code,
            mfo_pap_code.`name` as mfo_name,
            mfo_pap_code.description as mfo_description,
            mfo_pap_code.id as mfo_id,
            record_allotments.serial_number as allotment_number,
            chart_of_accounts.uacs as allotment_object_code,
            chart_of_accounts.general_ledger as allotment_account_title,
            major_accounts.`name` as allotment_class,
            ors_chart.uacs as obligation_object_code,
            ors_chart.general_ledger as obligation_account_title,
            process_ors_entries.amount as obligation_amount,
            t_obligation.total_obligation,

            ROUND((dv_aucs_entries.amount_disbursed *process_ors_entries.amount)/t_obligation.total_obligation,2) as dv_amount,
            ROUND((dv_aucs_entries.vat_nonvat*process_ors_entries.amount)/t_obligation.total_obligation,2) as dv_vat,
            ROUND((dv_aucs_entries.ewt_goods_services*process_ors_entries.amount)/t_obligation.total_obligation,2)as dv_ewt,
            ROUND((dv_aucs_entries.compensation*process_ors_entries.amount)/t_obligation.total_obligation,2) as dv_compensation,
            cash_disbursement.mode_of_payment,
            cash_disbursement.check_or_ada_no,
            cash_disbursement.ada_number,
            cash_disbursement.issuance_date,
            nature_of_transaction.`name` as nature_transaction_name,
            mrd_classification.`name` as mrd_name,
            dv_aucs.is_cancelled,
            document_recieve.`name` as doc_name,
            jev_preparation.jev_number,
            record_allotment_entries.amount as allotment_recieved,
            record_allotments.id as record_allotment_id,
            dv_aucs.book_id
            FROM dv_aucs
            LEFT JOIN dv_aucs_entries ON dv_aucs.id = dv_aucs_entries.dv_aucs_id
            LEFT JOIN process_ors ON dv_aucs_entries.process_ors_id = process_ors.id
            LEFT JOIN process_ors_entries ON process_ors.id = process_ors_entries.process_ors_id
            LEFT JOIN record_allotment_entries ON process_ors_entries.record_allotment_entries_id = record_allotment_entries.id
            LEFT JOIN	record_allotments ON record_allotment_entries.record_allotment_id = record_allotments.id
            LEFT JOIN mfo_pap_code ON record_allotments.mfo_pap_code_id = mfo_pap_code.id 
            LEFT JOIN chart_of_accounts ON record_allotment_entries.chart_of_account_id = chart_of_accounts.id
            LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id
            LEFT JOIN cash_disbursement ON dv_aucs.id = cash_disbursement.dv_aucs_id
            LEFT JOIN nature_of_transaction ON dv_aucs.nature_of_transaction_id = nature_of_transaction.id 
            LEFT JOIN mrd_classification on dv_aucs.mrd_classification_id = mrd_classification.id
            LEFT JOIN document_recieve ON record_allotments.document_recieve_id = document_recieve.id 
            LEFT JOIN jev_preparation ON dv_aucs.dv_number = jev_preparation.dv_number
            LEFT JOIN `transaction` ON process_ors.transaction_id = `transaction`.id
            LEFT JOIN payee ON dv_aucs.payee_id = payee.id
            LEFT JOIN(SELECT SUM(process_ors_entries.amount) as total_obligation,process_ors_entries.process_ors_id
            FROM process_ors_entries 

            GROUP BY process_ors_entries.process_ors_id) as t_obligation ON process_ors.id = t_obligation.process_ors_id

            LEFT JOIN chart_of_accounts  as ors_chart ON process_ors_entries.chart_of_account_id = ors_chart.id 
            WHERE dv_aucs.is_cancelled = 0 
        SQL;
        $this->execute($sql);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220308_060150_drop_detailed_dv_aucs_view cannot be reverted.\n";

        return false;
    }
    */
}
