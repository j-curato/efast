<?php

use yii\db\Migration;

/**
 * Class m230929_063847_create_prc_getDetailedProcessOrs_procedure
 */
class m230929_063847_create_prc_getDetailedProcessOrs_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP PROCEDURE IF EXISTS prc_GetDetailedProcessOrs;
            CREATE PROCEDURE prc_GetDetailedProcessOrs (IN yr INT,IN typ VARCHAR (255))
            BEGIN 
                    SELECT 
                    process_ors.serial_number as ors_num,
                    process_ors_entries.reporting_period,
                    process_ors.date as ors_date,
                    books.`name` as book_name,
                    `transaction`.tracking_number,
                    CONCAT(chart_of_accounts.uacs,'-',chart_of_accounts.general_ledger) as uacs,
                    process_ors_entries.amount,
                    record_allotment_detailed.allotmentNumber,
                    CONCAT(record_allotment_detailed.uacs,'-',record_allotment_detailed.account_title) as allotment_uacs,
                    record_allotment_detailed.amount as allotment_amt,
                    record_allotment_detailed.fund_source_name,
                    record_allotment_detailed.mfo_name
                    FROM 
                    process_ors
                    JOIN process_ors_entries ON process_ors.id = process_ors_entries.process_ors_id
                    JOIN books ON process_ors.book_id  = books.id
                    JOIN `transaction` ON process_ors.transaction_id  = `transaction`.id
                    LEFT JOIN chart_of_accounts ON process_ors_entries.chart_of_account_id = chart_of_accounts.id
                    LEFT JOIN record_allotment_detailed ON process_ors_entries.record_allotment_entries_id = record_allotment_detailed.allotment_entry_id
                    WHERE 
                    process_ors.type = typ
                    AND 
                    process_ors.reporting_period LIKE CONCAT(yr,'%');
            END 
        SQL;
        $this->execute($sql);
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
        echo "m230929_063847_create_prc_getDetailedProcessOrs_procedure cannot be reverted.\n";

        return false;
    }
    */
}
