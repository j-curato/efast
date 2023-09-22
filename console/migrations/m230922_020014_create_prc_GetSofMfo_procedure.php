<?php

use yii\db\Migration;

/**
 * Class m230922_020014_create_prc_GetSofMfo_procedure
 */
class m230922_020014_create_prc_GetSofMfo_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $sql = <<<SQL
            DROP PROCEDURE IF EXISTS prc_GetSofMfo;
            CREATE PROCEDURE prc_GetSofMfo(IN from_period VARCHAR(50), IN to_period VARCHAR(50))
            BEGIN
                WITH  
                    consoUsedAllotments as (
                        SELECT 
                            process_ors_entries.record_allotment_entries_id as allotment_entry_id,
                            SUM(process_ors_entries.amount) as ttlOrsAmt
                        FROM process_ors_entries
                        JOIN process_ors ON process_ors_entries.process_ors_id = process_ors.id
                        WHERE 
                        process_ors.is_cancelled = 0
                        AND process_ors_entries.reporting_period >=from_period
                        AND process_ors_entries.reporting_period <=to_period
                        GROUP BY process_ors_entries.record_allotment_entries_id
                    ),
                    cte_allotmentAdjustments as (
                        SELECT 
                            record_allotment_adjustments.fk_record_allotment_entry_id,
                            SUM( record_allotment_adjustments.amount) as ttl
                        FROM record_allotment_adjustments
                        WHERE 
                        record_allotment_adjustments.is_deleted = 0
                        GROUP BY record_allotment_adjustments.fk_record_allotment_entry_id
                    ),
                    cte_allotmentDetails as (
                        SELECT 
                            (CASE
                            WHEN record_allotments.isMaf =1 THEN UPPER(entryOffice.office_name)
                            ELSE UPPER(office.office_name)
                            END) as office_name,
                            (CASE
                            WHEN record_allotments.isMaf =1 THEN UPPER(entryDivision.division)
                            ELSE UPPER(divisions.division)
                            END) as division,
                            books.`name` as book_name,
                            major_accounts.`name` as allotment_class,
                            CONCAT(mfo_pap_code.`code`,'-',mfo_pap_code.`name`) as mfo_name,
                            document_recieve.`name` as document_recieve,
                            record_allotment_entries.amount,
                            COALESCE(consoUsedAllotments.ttlOrsAmt,0)as ttlOrsAmt,
                            COALESCE(cte_allotmentAdjustments.ttl,0) as ttlAdjustment,
                            COALESCE(record_allotment_entries.amount,0) - 	COALESCE(ABS(cte_allotmentAdjustments.ttl),0)-  COALESCE(consoUsedAllotments.ttlOrsAmt,0) as balAfterObligation,
                            record_allotments.reporting_period
                        FROM record_allotment_entries 
                        INNER JOIN record_allotments ON record_allotment_entries.record_allotment_id = record_allotments.id
                        LEFT JOIN mfo_pap_code ON record_allotments.mfo_pap_code_id = mfo_pap_code.id
                        LEFT JOIN chart_of_accounts ON record_allotment_entries.chart_of_account_id = chart_of_accounts.id
                        LEFT JOIN office ON record_allotments.office_id = office.id
                        lEFT JOIN divisions ON record_allotments.division_id = divisions.id
                        LEFT JOIN books ON record_allotments.book_id = books.id
                        LEFT JOIN consoUsedAllotments ON record_allotment_entries.id = consoUsedAllotments.allotment_entry_id
                        LEFT JOIN document_recieve ON record_allotments.document_recieve_id = document_recieve.id
                        LEFT JOIN major_accounts ON chart_of_accounts.major_account_id = major_accounts.id 
                        LEFT JOIN cte_allotmentAdjustments ON record_allotment_entries.id = cte_allotmentAdjustments.fk_record_allotment_entry_id
                        LEFT JOIN office as entryOffice ON record_allotment_entries.fk_office_id = entryOffice.id
                        LEFT JOIN divisions as entryDivision ON record_allotment_entries.fk_division_id = entryDivision.id
                        WHERE record_allotment_entries.is_deleted = 0 
                    )
                SELECT 
                    cte_allotmentDetails.book_name,
                    cte_allotmentDetails.allotment_class,
                    cte_allotmentDetails.mfo_name,
                    cte_allotmentDetails.document_recieve,
                    SUM(cte_allotmentDetails.amount) as ttlAllotment,
                    SUM(cte_allotmentDetails.ttlOrsAmt) as ttlOrs,
                    SUM(cte_allotmentDetails.ttlAdjustment) as ttlAdjustment,
                    SUM(cte_allotmentDetails.balAfterObligation) as ttlBalance
                FROM cte_allotmentDetails
                WHERE
                    cte_allotmentDetails.reporting_period >=from_period
                    AND cte_allotmentDetails.reporting_period <=to_period
                GROUP BY
                    cte_allotmentDetails.book_name,
                    cte_allotmentDetails.allotment_class,
                    cte_allotmentDetails.mfo_name,
                    cte_allotmentDetails.document_recieve
                ORDER BY cte_allotmentDetails.allotment_class DESC;

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
        echo "m230922_020014_create_prc_GetSofMfo_procedure cannot be reverted.\n";

        return false;
    }
    */
}
