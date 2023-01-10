<?php

use yii\db\Migration;

/**
 * Class m230110_031439_update_pr_allotment_view
 */
class m230110_031439_update_pr_allotment_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        YIi::$app->db->createCommand("DROP VIEW IF EXISTS pr_allotment_view;
        CREATE VIEW pr_allotment_view as SELECT 
        record_allotment_entries.id as allotment_entry_id,
        DATE_FORMAT(CONCAT(record_allotments.reporting_period,'-01'), '%Y') as budget_year,
        office.office_name,
        divisions.division,
        mfo_pap_code.`name` as mfo_name,
        fund_source.`name` as fund_source_name,
        chart_of_accounts.general_ledger as account_title,
        record_allotment_entries.amount,
        IFNULL(record_allotment_entries.amount,0) - IFNULL(total_pr_amt.ttl_pr_amount,0) as balance

        FROM record_allotment_entries 
        INNER JOIN record_allotments ON record_allotment_entries.record_allotment_id = record_allotments.id
        LEFT JOIN mfo_pap_code ON record_allotments.mfo_pap_code_id = mfo_pap_code.id
        LEFT JOIN fund_source ON record_allotments.fund_source_id = fund_source.id
        LEFT JOIN chart_of_accounts ON record_allotment_entries.chart_of_account_id = chart_of_accounts.id
        LEFT JOIN office ON record_allotments.office_id = office.id
        lEFT JOIN divisions ON record_allotments.division_id = divisions.id
        LEFT JOIN (SELECT
        pr_purchase_request_allotments.fk_record_allotment_entries_id,
        SUM(pr_purchase_request_allotments.amount) as ttl_pr_amount
        FROM pr_purchase_request_allotments
        WHERE pr_purchase_request_allotments.is_deleted = 0
        GROUP BY pr_purchase_request_allotments.fk_record_allotment_entries_id
        ) as total_pr_amt ON record_allotment_entries.id = total_pr_amt.fk_record_allotment_entries_id")
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230110_031439_update_pr_allotment_view cannot be reverted.\n";

        return false;
    }
    */
}
