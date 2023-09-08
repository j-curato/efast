<?php

use yii\db\Migration;

/**
 * Class m230908_014535_update_rao_procedure
 */
class m230908_014535_update_rao_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
        DROP PROCEDURE IF EXISTS rao;
        CREATE PROCEDURE rao(IN yr INT )
        BEGIN
            WITH 

            alltEntys as (
            SELECT 
            record_allotment_entries.id as allotment_entry_id,
            record_allotment_entries.amount as allotAmt,
            NULL as prId,
            NULL as txnId,
            NULL as orsId,
            0 as prAmt,
            0 as txnAmt,
            0 as orsAmt,
                    '' as ors_reporting_period
            FROM record_allotment_entries
            ),
            prAllots as (
            SELECT 
            pr_purchase_request_allotments.fk_record_allotment_entries_id as allotment_entry_id,
            0 as allotAmt,
            pr_purchase_request.id as prId,
            NULL as txnId,
            NULL as orsId,
            pr_purchase_request_allotments.amount as prAmt,
            0 as txnAmt,
            0 as orsAmt,
                '' as ors_reporting_period

            FROM pr_purchase_request_allotments 
            JOIN pr_purchase_request ON pr_purchase_request_allotments.fk_purchase_request_id = pr_purchase_request.id

            WHERE
            pr_purchase_request_allotments.is_deleted = 0
                AND pr_purchase_request.is_cancelled = 0
            ),
            txnPrAllots as (
            SELECT 
            pr_purchase_request_allotments.fk_record_allotment_entries_id as allotment_entry_id,
            0 as allotAmt,
            pr_purchase_request.id as prId,
            `transaction`.id as txnId,
            ors.id as orsId,
            transaction_pr_items.amount *-1 as prAmt,
            0 as txnAmt,
            0 as orsAmt,
            '' as ors_reporting_period
            FROM transaction_pr_items
            LEFT JOIN pr_purchase_request_allotments ON transaction_pr_items.fk_pr_allotment_id = pr_purchase_request_allotments.id
            JOIN pr_purchase_request ON pr_purchase_request_allotments.fk_purchase_request_id = pr_purchase_request.id
            JOIN `transaction` ON  transaction_pr_items.fk_transaction_id = `transaction`.id
            LEFT JOIN (
            SELECT process_ors.transaction_id,
            process_ors.id
            FROM process_ors WHERE process_ors.is_cancelled = 0
            ) as ors ON `transaction`.id = ors.transaction_id
            WHERE
            transaction_pr_items.is_deleted = 0
            ORDER BY pr_purchase_request.pr_number),
            txnItms as (
            SELECT 
            transaction_items.fk_record_allotment_entries_id as allotment_entry_id,
            0 as allotAmt,
            NULL as prId,
            `transaction`.id as txnId,
            ors.id as orsId,
            0 as prAmt,
            transaction_items.amount as txnAmt,
            0 as orsAmt,
                    '' as ors_reporting_period
            FROM transaction_items
            JOIN `transaction` ON transaction_items.fk_transaction_id = `transaction`.id
            LEFT JOIN (SELECT process_ors.id ,process_ors.transaction_id FROM process_ors WHERE  process_ors.is_cancelled = 0) as ors ON `transaction`.id = ors.transaction_id
            WHERE
            transaction_items.is_deleted = 0
            ),
            orsTxnItms as (
            SELECT 
            transaction_items.fk_record_allotment_entries_id as allotment_entry_id,
            0 as allotAmt,
            NULL as prId,
            `transaction_items`.fk_transaction_id as txnId,
            process_ors_txn_items.fk_process_ors_id,
            0 as prAmt,
            process_ors_txn_items.amount,
            0 as orsAmt,
                '' as ors_reporting_period
            FROM process_ors_txn_items
            JOIN transaction_items ON process_ors_txn_items.fk_transaction_item_id = transaction_items.id
            JOIN process_ors ON process_ors_txn_items.fk_process_ors_id = process_ors.id
            WHERE
            process_ors_txn_items.is_deleted = 0
            AND process_ors.is_cancelled = 0

            ),
            orsItms as (
            SELECT 
            process_ors_entries.record_allotment_entries_id as allotment_entry_id,
            0 as allotAmt,
            NULL as prId,
            process_ors.transaction_id as txnId,
            process_ors.id as orsId,
            0 as prAmt,
            0 as txnAmt,
            process_ors_entries.amount as orsAmt,

                (CASE
                            WHEN  process_ors_entries.reporting_period IS NULL  THEN process_ors.reporting_period
                            ELSE process_ors_entries.reporting_period
                    END) as ors_reporting_period
            FROM process_ors_entries
            JOIN process_ors ON process_ors_entries.process_ors_id = process_ors.id
            WHERE
            process_ors.is_cancelled = 0
            ),
            consoAllotments as (


            SELECT * FROM alltEntys
            UNION  ALL
            SELECT * FROM prAllots
            UNION ALL
            SELECT * FROM txnPrAllots
            UNION ALL
            SELECT * FROM txnItms
            UNION ALL 
            SELECT * FROM orsTxnItms
            UNION ALL 
            SELECT * FROM orsItms
            )
            SELECT 
            record_allotment_detailed.budget_year,
            record_allotment_detailed.office_name,
            record_allotment_detailed.division,
            record_allotment_detailed.allotmentNumber,
            record_allotment_detailed.mfo_name,
            record_allotment_detailed.fund_source_name,
            record_allotment_detailed.book_name,
            record_allotment_detailed.account_title,
            pr_purchase_request.pr_number,
            pr_purchase_request.date as prDate,
            pr_purchase_request.purpose as prPurpose,
            `transaction`.tracking_number as transaction_num,
            `transaction`.transaction_date as txnDate,
            `transaction`.particular as txnParticular,
            payee.account_name as txnPayee,
            process_ors.serial_number as orsNum,
                consoAllotments.ors_reporting_period,
            consoAllotments.allotAmt,
            consoAllotments.prAmt,
            consoAllotments.txnAmt,
            consoAllotments.orsAmt

            FROM consoAllotments
            LEFT JOIN record_allotment_detailed ON consoAllotments.allotment_entry_id  = record_allotment_detailed.allotment_entry_id
            LEFT JOIN pr_purchase_request ON consoAllotments.prId = pr_purchase_request.id
            LEFT JOIN `transaction` ON consoAllotments.txnId = `transaction`.id
            LEFT JOIN process_ors ON consoAllotments.orsId = process_ors.id
            LEFT JOIN payee ON `transaction`.payee_id = payee.id
            WHERE 
            record_allotment_detailed.budget_year = yr
            ORDER BY
            record_allotment_detailed.allotmentNumber,
            consoAllotments.allotAmt DESC,
            pr_purchase_request.pr_number DESC,
            consoAllotments.prAmt DESC,
            `transaction`.tracking_number DESC,
            consoAllotments.txnAmt DESC,
            process_ors.serial_number DESC;


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
        echo "m230908_014535_update_rao_procedure cannot be reverted.\n";

        return false;
    }
    */
}
