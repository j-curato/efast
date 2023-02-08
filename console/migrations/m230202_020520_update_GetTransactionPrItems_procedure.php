<?php

use yii\db\Migration;

/**
 * Class m230202_020520_update_GetTransactionPrItems_procedure
 */
class m230202_020520_update_GetTransactionPrItems_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        try {
            $sql = <<<SQL
                DROP PROCEDURE IF EXISTS GetTransactionPrItems;
                CREATE PROCEDURE GetTransactionPrItems(IN transaction_id BIGINT)
                BEGIN 
                    SELECT 
                    transaction_pr_items.id,
                    pr_purchase_request.pr_number,
                    record_allotments.serial_number as allotment_number,
                    pr_purchase_request_allotments.id as prAllotmentId,
                    pr_purchase_request_allotments.amount as prAllotmenAmt,
                    UPPER(office.office_name) as office_name,
                    UPPER(divisions.division) as division ,
                    CONCAT(mfo_pap_code.`code`,'-',mfo_pap_code.`name`) as mfo_name,
                    fund_source.`name` as fund_source_name,
                    chart_of_accounts.general_ledger as account_title,
                    pr_purchase_request_allotments.amount as prAllotmentAmt,
                    IFNULL(pr_purchase_request_allotments.amount,0) - IFNULL(ttlTransaction.ttlTransactAmt,0) as balance,
                    books.`name` as book_name,
                    ttlTransaction.ttlTransactAmt,
                    pr_purchase_request.purpose,
                    transaction_pr_items.amount as txnPrAmt
                    FROM transaction_pr_items 
        
                    INNER JOIN pr_purchase_request_allotments ON transaction_pr_items.fk_pr_allotment_id = pr_purchase_request_allotments.id
                    INNER JOIN record_allotment_entries ON pr_purchase_request_allotments.fk_record_allotment_entries_id = record_allotment_entries.id
                    INNER JOIN record_allotments ON record_allotment_entries.record_allotment_id = record_allotments.id
                    LEFT JOIN mfo_pap_code ON record_allotments.mfo_pap_code_id = mfo_pap_code.id
                    LEFT JOIN fund_source ON record_allotments.fund_source_id = fund_source.id
                    LEFT JOIN chart_of_accounts ON record_allotment_entries.chart_of_account_id = chart_of_accounts.id
                    LEFT JOIN office ON record_allotments.office_id = office.id
                    lEFT JOIN divisions ON record_allotments.division_id = divisions.id
                    LEFT JOIN books ON record_allotments.book_id = books.id
                    LEFT JOIN pr_purchase_request ON pr_purchase_request_allotments.fk_purchase_request_id = pr_purchase_request.id
                    LEFT JOIN (SELECT
                    transaction_pr_items.fk_pr_allotment_id,
                    SUM(transaction_pr_items.amount) as ttlTransactAmt
                    FROM transaction_pr_items
                    WHERE transaction_pr_items.is_deleted = 0
                    GROUP BY transaction_pr_items.fk_pr_allotment_id
                    ) as ttlTransaction ON pr_purchase_request_allotments.id = ttlTransaction.fk_pr_allotment_id
                    WHERE 
                    transaction_pr_items.fk_transaction_id = transaction_id
                    AND transaction_pr_items.is_deleted = 0;
                END 
            SQL;
            $this->execute($sql);
        } catch (ErrorException $e) {
            return $e->getMessage();
        }
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
        echo "m230202_020520_update_GetTransactionPrItems_procedure cannot be reverted.\n";

        return false;
    }
    */
}
