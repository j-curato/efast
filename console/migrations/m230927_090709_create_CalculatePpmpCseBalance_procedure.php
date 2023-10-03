<?php

use yii\db\Migration;

/**
 * Class m230927_090709_create_CalculatePpmpCseBalance_procedure
 */
class m230927_090709_create_CalculatePpmpCseBalance_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<< SQL
                  DROP PROCEDURE IF EXISTS CalculatePpmpCseBalance;
                  CREATE PROCEDURE CalculatePpmpCseBalance(
                            IN prItemId VARCHAR(255),
                            IN ppmpId BIGINT,
                            IN amount DECIMAL(10,2),
                            IN qty INT
                    )
                    BEGIN 
                        DECLARE prItemQry VARCHAR(1024) DEFAULT NULL;
                        SET  prItemQry =  '';
                        IF prItemId IS NOT NULL THEN
                            SET  prItemQry =  CONCAT(' AND pr_purchase_request_item.id != ',prItemId);
                        END IF;
                        SET @finalQuery = CONCAT("WITH cteGetPpmp AS (
                           SELECT
                                COALESCE(supplemental_ppmp_cse.jan_qty, 0) +
                                COALESCE(supplemental_ppmp_cse.feb_qty, 0) +
                                COALESCE(supplemental_ppmp_cse.mar_qty, 0) +
                                COALESCE(supplemental_ppmp_cse.apr_qty, 0) +
                                COALESCE(supplemental_ppmp_cse.may_qty, 0) +
                                COALESCE(supplemental_ppmp_cse.jun_qty, 0) +
                                COALESCE(supplemental_ppmp_cse.jul_qty, 0) +
                                COALESCE(supplemental_ppmp_cse.aug_qty, 0) +
                                COALESCE(supplemental_ppmp_cse.sep_qty, 0) +
                                COALESCE(supplemental_ppmp_cse.oct_qty, 0) +
                                COALESCE(supplemental_ppmp_cse.nov_qty, 0) +
                                COALESCE(supplemental_ppmp_cse.dec_qty, 0) AS monthly_qty,
                                COALESCE(ttlInPr.ttlPrQty, 0) AS total_pr_qty,
                                COALESCE(supplemental_ppmp_cse.amount, 0) AS item_amount,
                                COALESCE(ttlInPr.ttlPr, 0) AS total_pr_amount
                            FROM supplemental_ppmp_cse
                            LEFT JOIN (
                                SELECT
                                pr_purchase_request_item.fk_ppmp_cse_item_id,
                                SUM(pr_purchase_request_item.quantity * pr_purchase_request_item.unit_cost) AS ttlPr,
                                SUM(pr_purchase_request_item.quantity) AS ttlPrQty
                                FROM pr_purchase_request_item
                                JOIN pr_purchase_request ON pr_purchase_request_item.pr_purchase_request_id = pr_purchase_request.id
                                WHERE pr_purchase_request_item.is_deleted = 0
                                AND pr_purchase_request.is_cancelled = 0
                                ",prItemQry,"
                                GROUP BY pr_purchase_request_item.fk_ppmp_cse_item_id
                            ) AS ttlInPr ON supplemental_ppmp_cse.id = ttlInPr.fk_ppmp_cse_item_id
                            WHERE
                            supplemental_ppmp_cse.id = ",ppmpId,"
                            )
                            SELECT
                            monthly_qty - total_pr_qty AS qtyBal,
                            monthly_qty - total_pr_qty - ",qty," AS newQtyBal,
                            monthly_qty * item_amount - total_pr_amount  AS amtBal,
                            monthly_qty * item_amount - total_pr_amount - ",amount," AS newAmtBal
                            FROM cteGetPpmp");
                        PREPARE stmt FROM @finalQuery;
                        EXECUTE stmt;
                        DEALLOCATE PREPARE stmt;
                        
                        END ;
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
        echo "m230927_090709_create_CalculatePpmpCseBalance_procedure cannot be reverted.\n";

        return false;
    }
    */
}
