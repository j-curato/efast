<?php

use yii\db\Migration;

/**
 * Class m210811_075000_create_record_allotment_for_ors_view
 */
class m210811_075000_create_record_allotment_for_ors_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql =<<<SQL
        CREATE VIEW record_allotment_for_ors as 
        SELECT
        record_allotment_entries.id,
        record_allotments.serial_number,
        mfo_pap_code.`code`,
        mfo_pap_code.`name` as mfo_name,
        fund_source.`name` as fund_source_name,
        chart_of_accounts.uacs,
        chart_of_accounts.general_ledger,
        record_allotment_entries.amount,
        record_allotment_entries.amount - q.total_ors as balance


        FROM record_allotment_entries
        LEFT JOIN record_allotments ON record_allotment_entries.record_allotment_id = record_allotments.id
        LEFT JOIN mfo_pap_code ON record_allotments.mfo_pap_code_id = mfo_pap_code.id
        LEFT JOIN fund_source ON record_allotments.fund_source_id = fund_source.id
        LEFT JOIN chart_of_accounts ON record_allotment_entries.chart_of_account_id = chart_of_accounts.id
        LEFT JOIN (

        SELECT 
        raouds.record_allotment_entries_id,
        SUM(raoud_entries.amount) as total_ors
        FROM raouds
        LEFT JOIN raoud_entries ON raouds.id = raoud_entries.raoud_id
        WHERE raouds.process_ors_id IS NOT NULL
        GROUP BY raouds.record_allotment_entries_id
        ) as q ON record_allotment_entries.id = q.record_allotment_entries_id
        SQL;

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand('DROP VIEW IF EXISTS record_allotment_for_ors ')->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210811_075000_create_record_allotment_for_ors_view cannot be reverted.\n";

        return false;
    }
    */
}
