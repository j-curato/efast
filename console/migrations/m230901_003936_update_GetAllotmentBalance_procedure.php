<?php

use yii\db\Migration;

/**
 * Class m230901_003936_update_GetAllotmentBalance_procedure
 */
class m230901_003936_update_GetAllotmentBalance_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        YIi::$app->db->createCommand(` DROP PROCEDURE IF EXISTS GetAllotmentBalance;
        DELIMITER //
        CREATE PROCEDURE GetAllotmentBalance(
        IN allotmentEntryId VARCHAR(1024),
        IN prAllotmentId VARCHAR(1024),
        IN txnAllotmentId VARCHAR(1024),
        IN txnPrItmId VARCHAR(1024),
        IN orsItmId VARCHAR(1024),
        IN orsTxnItmId VARCHAR(1024)
         )
        BEGIN 
        DECLARE prAllotmentQry VARCHAR(1024) DEFAULT NULL;
        DECLARE txnAllotmentQry VARCHAR(1024) DEFAULT NULL;
        DECLARE txnPrItmQry VARCHAR(1024)DEFAULT NULL;
        DECLARE orsItmQry VARCHAR(1024)DEFAULT NULL;
        DECLARE orsTxnItmQry VARCHAR(1024) DEFAULT NULL;
         SET  prAllotmentQry =  '';
         SET  txnAllotmentQry =  '';
         SET  txnPrItmQry =  '';
         SET  orsItmQry =  '';
         SET  orsTxnItmQry =  '';
         IF prAllotmentId IS NOT NULL THEN
             SET  prAllotmentQry =  CONCAT(' AND pr_purchase_request_allotments.id != ',prAllotmentId);
         END IF;
        
         IF txnAllotmentId IS NOT NULL THEN
             SET  txnAllotmentQry =  CONCAT(' AND transaction_items.id != ',txnAllotmentId);
         END IF;
         IF txnPrItmId IS NOT NULL THEN
             SET  txnPrItmQry =  CONCAT(' AND transaction_pr_items.id != ',txnPrItmId);
         END IF;
         IF orsItmId IS NOT NULL THEN
             SET  orsItmQry =  CONCAT(' AND process_ors_entries.id != ',orsItmId);
         END IF;
         IF orsTxnItmId IS NOT NULL THEN
             SET  orsTxnItmQry =  CONCAT(' AND process_ors_txn_items.id != ',orsTxnItmId);
         END IF;
        
        
         SET @finalQuery = CONCAT('WITH detailedUsedAllotments as
        (
            SELECT 
          pr_purchase_request_allotments.fk_record_allotment_entries_id as allotment_entry_id,
          0 as orsAmt,
          pr_purchase_request_allotments.amount as prAmt,
          0 as trAmt
          FROM  pr_purchase_request_allotments 
          JOIN pr_purchase_request ON pr_purchase_request_allotments.fk_purchase_request_id = pr_purchase_request.id
          WHERE pr_purchase_request_allotments.is_deleted = 0  AND pr_purchase_request.is_cancelled = 0',
            prAllotmentQry,
            "  UNION ALL 
             SELECT 
           transaction_items.fk_record_allotment_entries_id as allotment_entry_id,
           0 as orsAmt,
           0 as prAmt,
           transaction_items.amount as trAmt
           FROM 
           transaction_items 
           WHERE transaction_items.is_deleted = 0",
            txnAllotmentQry,
        " UNION ALL 
         SELECT 
         pr_purchase_request_allotments.fk_record_allotment_entries_id as allotment_entry_id,
         0 as orsAmt,
         transaction_pr_items.amount*-1 as prAmt,
         0 as trAmt
         FROM transaction_pr_items
         INNER JOIN pr_purchase_request_allotments ON transaction_pr_items.fk_pr_allotment_id = pr_purchase_request_allotments.id
        WHERE transaction_pr_items.is_deleted = 0",
        txnPrItmQry,
        " UNION ALL 
        SELECT 
        process_ors_entries.record_allotment_entries_id as allotment_entry_id,
        process_ors_entries.amount as orsAmt,
        0 as prAmt,
        0 trAmt
        FROM process_ors_entries
				JOIN process_ors ON process_ors_entries.process_ors_id = process_ors.id
				WHERE process_ors.is_cancelled = 0",
        orsItmQry,
        " UNION ALL 
         SELECT 
         transaction_items.fk_record_allotment_entries_id as allotment_entry_id,
         0 as orsAmt,
         0 as prAmt,
         process_ors_txn_items.amount as trAmt
         FROM 
         process_ors_txn_items
         LEFT JOIN transaction_items ON process_ors_txn_items.fk_transaction_item_id = transaction_items.id
						JOIN process_ors ON process_ors_txn_items.fk_process_ors_id = process_ors.id
         WHERE process_ors_txn_items.is_deleted = 0 AND process_ors.is_cancelled = 0",
        orsTxnItmQry,
        "),
        consoUsedAllotments as (
        SELECT 
        detailedUsedAllotments.allotment_entry_id,
        SUM(detailedUsedAllotments.orsAmt) as ttlOrsAmt,
        SUM(prAmt) as ttlPrAmt,
        SUM(trAmt) as ttlTrAmt
        FROM detailedUsedAllotments
        GROUP BY detailedUsedAllotments.allotment_entry_id
        )
        SELECT 
        
        COALESCE(record_allotment_entries.amount,0)-
        COALESCE(consoUsedAllotments.ttlOrsAmt,0)-
        COALESCE(consoUsedAllotments.ttlPrAmt,0)-
        COALESCE(consoUsedAllotments.ttlTrAmt,0) as balance
        FROM record_allotment_entries 
        LEFT JOIN consoUsedAllotments ON record_allotment_entries.id = consoUsedAllotments.allotment_entry_id
		
        WHERE record_allotment_entries.id = ",
        allotmentEntryId
        );

         PREPARE stmt FROM @finalQuery;
         EXECUTE stmt;
          DEALLOCATE PREPARE stmt;
        
        END //
        DELIMITER ;`)
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
        echo "m230901_003936_update_GetAllotmentBalance_procedure cannot be reverted.\n";

        return false;
    }
    */
}
