<?php

use yii\db\Migration;

/**
 * Class m210928_033023_create_saob_ors_total_view
 */
class m210928_033023_create_saob_ors_total_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
        CREATE VIEW saob_ors_total as 
            SELECT
            record_allotments_view.mfo_code ,
            record_allotments_view.document_recieve,
            process_ors_entries.reporting_period,
            chart_of_accounts.uacs,
            SUM(process_ors_entries.amount) as ors_total

            FROM process_ors_entries
            LEFT JOIN chart_of_accounts ON process_ors_entries.chart_of_account_id  = chart_of_accounts.id
            LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id
            LEFT JOIN record_allotments_view ON process_ors_entries.record_allotment_entries_id = record_allotments_view.entry_id
            WHERE

            major_accounts.object_code IN (5020000000,5060000000,5010000000)

            GROUP BY 
            record_allotments_view.mfo_code ,
            record_allotments_view.document_recieve,
            process_ors_entries.reporting_period,
            chart_of_accounts.uacs
            ORDER BY record_allotments_view.mfo_code ,
            record_allotments_view.document_recieve,
            process_ors_entries.reporting_period,
            chart_of_accounts.uacs
        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXIST saob_ors_total")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210928_033023_create_saob_ors_total_view cannot be reverted.\n";

        return false;
    }
    */
}
