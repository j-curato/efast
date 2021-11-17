<?php

use yii\db\Migration;

/**
 * Class m211001_070304_create_saob_rao_view
 */
class m211001_070304_create_saob_rao_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL

        DROP VIEW IF EXISTS saob_rao;
        CREATE VIEW saob_rao as 
                SELECT
                process_ors_entries.reporting_period,
                process_ors_entries.chart_of_account_id,
                record_allotments.book_id,
                record_allotments.mfo_pap_code_id,
                record_allotments.document_recieve_id,
                process_ors_entries.amount as ors_amount,
                0 as allotment_amount,
                mfo_pap_code.division,
                major_accounts.id as major_id
                FROM process_ors_entries
                LEFT JOIN record_allotment_entries ON process_ors_entries.record_allotment_entries_id = record_allotment_entries.id
                LEFT JOIN record_allotments ON record_allotment_entries.record_allotment_id = record_allotments.id
                LEFT JOIN mfo_pap_code ON record_allotments.mfo_pap_code_id = mfo_pap_code.id
                LEFT JOIN chart_of_accounts ON   process_ors_entries.chart_of_account_id = chart_of_accounts.id
                LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id
                LEFT JOIN process_ors ON process_ors_entries.process_ors_id = process_ors.id
                WHERE process_ors.is_cancelled = 0
                UNION ALL
                SELECT 
                record_allotments.reporting_period,
                record_allotment_entries.chart_of_account_id,
                record_allotments.book_id,
                record_allotments.mfo_pap_code_id,
                record_allotments.document_recieve_id,
                0 as ors_amount,
                record_allotment_entries.amount as allotment_amount,
                mfo_pap_code.division,
                major_accounts.id
                FROM record_allotment_entries
                LEFT JOIN record_allotments ON record_allotment_entries.record_allotment_id = record_allotments.id 
                LEFT JOIN mfo_pap_code ON record_allotments.mfo_pap_code_id = mfo_pap_code.id 
                LEFT JOIN chart_of_accounts ON   record_allotment_entries.chart_of_account_id = chart_of_accounts.id
                LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id 
        SQL;
        $this->execute($sql);
    }

    /**     
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS saob_rao")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211001_070304_create_saob_rao_view cannot be reverted.\n";

        return false;
    }
    */
}
