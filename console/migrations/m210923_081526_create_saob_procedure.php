<?php

use yii\db\Migration;

/**
 * Class m210923_081526_create_saob_procedure
 */
class m210923_081526_create_saob_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql  = <<<SQL
            DROP PROCEDURE IF EXISTS saob;
            CREATE PROCEDURE saob(from_reporting_period VARCHAR(20),to_reporting_period VARCHAR(20),document VARCHAR(100),mfo_code VARCHAR(20))
            BEGIN
                SELECT 
                chart.*,
                IFNULL(prev_ors.prev_total,0) as prev_total,
                IFNULL(current_ors.current_total,0) as current_total,
                IFNULL(allotment.total_allotment,0) as total_allotment,
                IFNULL(prev_ors.prev_total,0)+
                IFNULL(current_ors.current_total,0) as ors_to_date

                FROM 
                (SELECT
                major_accounts.object_code as major_object_code,
                major_accounts.`name` as major_name,
                sub_major_accounts.object_code as sub_major_object_code,
                sub_major_accounts.`name` as sub_major_name,
                chart_of_accounts.uacs,
                chart_of_accounts.general_ledger

                FROM  chart_of_accounts 
                LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id
                LEFT JOIN sub_major_accounts ON chart_of_accounts.sub_major_account = sub_major_accounts.id

                WHERE
                major_accounts.object_code IN (5010000000,5020000000,5060000000)


                ) as chart

                LEFT JOIN (
                SELECT
                chart_of_accounts.uacs,
                SUM(process_ors_entries.amount) as current_total

                FROM process_ors_entries
                LEFT JOIN chart_of_accounts ON process_ors_entries.chart_of_account_id  = chart_of_accounts.id
                LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id
                LEFT JOIN record_allotments_view ON process_ors_entries.record_allotment_entries_id = record_allotments_view.entry_id
                WHERE
                process_ors_entries.reporting_period >= from_reporting_period
                AND process_ors_entries.reporting_period <= to_reporting_period
                AND major_accounts.object_code IN (5020000000,5060000000,5010000000)
                AND record_allotments_view.mfo_code = mfo_code
                AND record_allotments_view.document_recieve = document
                GROUP BY 
                chart_of_accounts.uacs


                ) as current_ors
                ON (chart.uacs = current_ors.uacs )
                LEFT JOIN (
                SELECT
                chart_of_accounts.uacs,
                SUM(process_ors_entries.amount) as prev_total

                FROM process_ors_entries
                LEFT JOIN chart_of_accounts ON process_ors_entries.chart_of_account_id  = chart_of_accounts.id
                LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id
                LEFT JOIN record_allotments_view ON process_ors_entries.record_allotment_entries_id = record_allotments_view.entry_id
                WHERE
                process_ors_entries.reporting_period <from_reporting_period

                AND major_accounts.object_code IN (5020000000,5060000000,5010000000)
                AND record_allotments_view.mfo_code = mfo_code
                AND record_allotments_view.document_recieve = document
                GROUP BY 
                chart_of_accounts.uacs
                ) as prev_ors
                ON (chart.uacs = prev_ors.uacs )
                LEFT JOIN (
                        SELECT
                        record_allotments_view.uacs,
                        SUM(record_allotments_view.amount) as total_allotment
                        FROM record_allotments_view
                        WHERE
                        -- record_allotments_view.mfo_code = mfo_code
                        -- AND record_allotments_view.document_recieve = document
                        
                        GROUP BY record_allotments_view.uacs
                ) as allotment
                ON (chart.uacs = allotment.uacs )
                WHERE
                prev_ors.prev_total >0
                OR current_ors.current_total >0
                OR allotment.total_allotment >0;
            END


        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP PROCEDURE IF EXISTS saob")->query();

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210923_081526_create_saob_procedure cannot be reverted.\n";

        return false;
    }
    */
}
