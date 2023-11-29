<?php

use yii\db\Migration;

/**
 * Class m231129_062758_update_supplemental_ppmp_index_view
 */
class m231129_062758_update_supplemental_ppmp_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<< SQL
            DROP VIEW IF EXISTS  supplemental_ppmp_index;
            CREATE VIEW supplemental_ppmp_index AS
              WITH 
                cseTtlPr as (
                    SELECT 
                    pr_purchase_request_item.fk_ppmp_cse_item_id,
                    SUM(pr_purchase_request_item.quantity *pr_purchase_request_item.unit_cost) as total_pr_amt,
                    SUM(pr_purchase_request_item.quantity) as total_pr_qty
                    FROM pr_purchase_request_item
                    JOIN pr_purchase_request ON pr_purchase_request_item.pr_purchase_request_id = pr_purchase_request.id
                    WHERE pr_purchase_request_item.is_deleted = 0
                    AND pr_purchase_request_item.fk_ppmp_cse_item_id IS NOT NULL
                    AND pr_purchase_request.is_cancelled = 0
                    GROUP BY pr_purchase_request_item.fk_ppmp_cse_item_id
                ),
                cseBal as (
                    SELECT 
                    supplemental_ppmp_cse.id,
                    COALESCE(supplemental_ppmp_cse.jan_qty,0)+
                    COALESCE(supplemental_ppmp_cse.feb_qty,0)+
                    COALESCE(supplemental_ppmp_cse.mar_qty,0)+
                    COALESCE(supplemental_ppmp_cse.apr_qty,0)+
                    COALESCE(supplemental_ppmp_cse.may_qty,0)+
                    COALESCE(supplemental_ppmp_cse.jun_qty,0)+
                    COALESCE(supplemental_ppmp_cse.jul_qty,0)+
                    COALESCE(supplemental_ppmp_cse.aug_qty,0)+
                    COALESCE(supplemental_ppmp_cse.sep_qty,0)+
                    COALESCE(supplemental_ppmp_cse.oct_qty,0)+
                    COALESCE(supplemental_ppmp_cse.nov_qty,0)+
                    COALESCE(supplemental_ppmp_cse.dec_qty,0) as ttl_qty, 

                    (
                        COALESCE(supplemental_ppmp_cse.jan_qty,0)+
                        COALESCE(supplemental_ppmp_cse.feb_qty,0)+
                        COALESCE(supplemental_ppmp_cse.mar_qty,0)+
                        COALESCE(supplemental_ppmp_cse.apr_qty,0)+
                        COALESCE(supplemental_ppmp_cse.may_qty,0)+
                        COALESCE(supplemental_ppmp_cse.jun_qty,0)+
                        COALESCE(supplemental_ppmp_cse.jul_qty,0)+
                        COALESCE(supplemental_ppmp_cse.aug_qty,0)+
                        COALESCE(supplemental_ppmp_cse.sep_qty,0)+
                        COALESCE(supplemental_ppmp_cse.oct_qty,0)+
                        COALESCE(supplemental_ppmp_cse.nov_qty,0)+
                        COALESCE(supplemental_ppmp_cse.dec_qty,0)
                    ) - COALESCE(cseTtlPr.total_pr_qty,0) as bal_qty,
                    (
                        COALESCE(supplemental_ppmp_cse.jan_qty,0)+
                        COALESCE(supplemental_ppmp_cse.feb_qty,0)+
                        COALESCE(supplemental_ppmp_cse.mar_qty,0)+
                        COALESCE(supplemental_ppmp_cse.apr_qty,0)+
                        COALESCE(supplemental_ppmp_cse.may_qty,0)+
                        COALESCE(supplemental_ppmp_cse.jun_qty,0)+
                        COALESCE(supplemental_ppmp_cse.jul_qty,0)+
                        COALESCE(supplemental_ppmp_cse.aug_qty,0)+
                        COALESCE(supplemental_ppmp_cse.sep_qty,0)+
                        COALESCE(supplemental_ppmp_cse.oct_qty,0)+
                        COALESCE(supplemental_ppmp_cse.nov_qty,0)+
                        COALESCE(supplemental_ppmp_cse.dec_qty,0)
                    ) * COALESCE(supplemental_ppmp_cse.amount,0) - COALESCE(cseTtlPr.total_pr_amt,0) as bal_amt
                    FROM supplemental_ppmp_cse 
                    LEFT JOIN cseTtlPr ON supplemental_ppmp_cse.id = cseTtlPr.fk_ppmp_cse_item_id
                    WHERE supplemental_ppmp_cse.is_deleted = 0 
                ),

                cse as (
                    SELECT 
                    supplemental_ppmp_cse.id as cse_id,
                supplemental_ppmp_cse.fk_supplemental_ppmp_id,
                pr_stock.stock_title as stock_activity,
                    COALESCE(supplemental_ppmp_cse.amount,0) * COALESCE(cseBal.ttl_qty,0) as gross_amt,
                    cseBal.bal_amt,
                    cseBal.ttl_qty,
                    cseBal.bal_qty

                    FROM  supplemental_ppmp_cse 
                    LEFT JOIN cseBal ON supplemental_ppmp_cse.id = cseBal.id
                LEFT JOIN pr_stock ON supplemental_ppmp_cse.fk_pr_stock_id = pr_stock.id
                    WHERE supplemental_ppmp_cse.is_deleted = 0 
                ),
                nonCseTtlPr as (
                    
                    SELECT 
                    pr_purchase_request_item.fk_ppmp_non_cse_item_id,
                    SUM(pr_purchase_request_item.unit_cost * pr_purchase_request_item.quantity) as ttl_pr_amt
                    FROM pr_purchase_request_item
                    JOIN pr_purchase_request ON pr_purchase_request_item.pr_purchase_request_id = pr_purchase_request.id
                    WHERE pr_purchase_request_item.is_deleted = 0
                    AND  pr_purchase_request_item.fk_ppmp_non_cse_item_id IS NOT NULL
                    AND pr_purchase_request.is_cancelled = 0
                    GROUP BY
                    pr_purchase_request_item.fk_ppmp_non_cse_item_id

                ),
                nonCseItmsBal as (
                    SELECT 
                        supplemental_ppmp_non_cse_items.id,
                        supplemental_ppmp_non_cse_items.fk_supplemental_ppmp_non_cse_id,
                    COALESCE(nonCseTtlPr.ttl_pr_amt,0) as ttlPr,
                    COALESCE(supplemental_ppmp_non_cse_items.amount,0) - COALESCE(nonCseTtlPr.ttl_pr_amt,0) as balance
                    FROM supplemental_ppmp_non_cse_items
                    LEFT JOIN nonCseTtlPr ON supplemental_ppmp_non_cse_items.id   = nonCseTtlPr.fk_ppmp_non_cse_item_id
                    WHERE supplemental_ppmp_non_cse_items.is_deleted = 0
                ),
                nonCseAct as (

                SELECT 
                supplemental_ppmp_non_cse.id as non_cse_id,
                supplemental_ppmp_non_cse.fk_supplemental_ppmp_id,
                supplemental_ppmp_non_cse.activity_name,
                itemsTtl.itmTtlAmt,
                itmTtlBal.ttlBal
                FROM supplemental_ppmp_non_cse
                LEFT JOIN (
                    SELECT 
                    supplemental_ppmp_non_cse_items.fk_supplemental_ppmp_non_cse_id,
                    SUM(supplemental_ppmp_non_cse_items.amount) as itmTtlAmt
                    FROM supplemental_ppmp_non_cse_items
                    WHERE 
                    supplemental_ppmp_non_cse_items.is_deleted = 0
                    GROUP BY supplemental_ppmp_non_cse_items.fk_supplemental_ppmp_non_cse_id
                ) as itemsTtl ON supplemental_ppmp_non_cse.id = itemsTtl.fk_supplemental_ppmp_non_cse_id
                LEFT JOIN (
                    SELECT
                        nonCseItmsBal.fk_supplemental_ppmp_non_cse_id,
                        SUM(nonCseItmsBal.balance) as ttlBal
                    FROM nonCseItmsBal
                    GROUP BY nonCseItmsBal.fk_supplemental_ppmp_non_cse_id
                ) as itmTtlBal ON supplemental_ppmp_non_cse.id = itmTtlBal.fk_supplemental_ppmp_non_cse_id
                WHERE 
                    supplemental_ppmp_non_cse.is_deleted = 0
                    AND supplemental_ppmp_non_cse.type = 'activity'
                ),
                nonCseFix as (
                SELECT 
                supplemental_ppmp_non_cse_items.id as non_cse_id,
                supplemental_ppmp_non_cse.fk_supplemental_ppmp_id,
                CONCAT('Fixed Expense - ',pr_stock.stock_title) as stock_title,
                supplemental_ppmp_non_cse_items.amount,
                nonCseItmsBal.balance
                FROM supplemental_ppmp_non_cse
                INNER JOIN supplemental_ppmp_non_cse_items ON supplemental_ppmp_non_cse.id  = supplemental_ppmp_non_cse_items.fk_supplemental_ppmp_non_cse_id
                LEFT JOIN nonCseItmsBal ON supplemental_ppmp_non_cse_items.id = nonCseItmsBal.id
                LEFT JOIN pr_stock ON supplemental_ppmp_non_cse_items.fk_pr_stock_id = pr_stock.id
                WHERE 
                supplemental_ppmp_non_cse.is_deleted  = 0 
                AND supplemental_ppmp_non_cse_items.is_deleted = 0 
                AND supplemental_ppmp_non_cse.type ='fixed expenses'


                )

                SELECT 
                supplemental_ppmp.id,
                supplemental_ppmp.budget_year,
                supplemental_ppmp.cse_type,
                supplemental_ppmp.serial_number,
                office.office_name,
                divisions.division,
                division_program_unit.`name` as division_program_unit_name,
                prepared_by.employee_name as prepared_by,
                reviewed_by.employee_name as reviewed_by,
                approved_by.employee_name as approved_by,
                certified_avail.employee_name as certified_avail,
                fnl.stock_activity,
                fnl.gross_amt,
                fnl.bal_amt,
                fnl.ttl_qty,
                fnl.bal_qty
                FROM supplemental_ppmp
                LEFT JOIN  (
                    SELECT * FROM cse
                    UNION 
                    SELECT nonCseAct.*,0,0 FROM nonCseAct
                    UNION 
                    SELECT nonCseFix.*,0,0 FROM nonCseFix
                ) as  fnl ON supplemental_ppmp.id = fnl.fk_supplemental_ppmp_id
                LEFT JOIN office ON supplemental_ppmp.fk_office_id = office.id
                LEFT JOIN divisions ON supplemental_ppmp.fk_division_id = divisions.id
                LEFT JOIN division_program_unit ON supplemental_ppmp.fk_division_program_unit_id = division_program_unit.id
                LEFT JOIN employee_search_view as prepared_by ON supplemental_ppmp.fk_prepared_by = prepared_by.employee_id
                LEFT JOIN employee_search_view as reviewed_by ON supplemental_ppmp.fk_reviewed_by = reviewed_by.employee_id
                LEFT JOIN employee_search_view as approved_by ON supplemental_ppmp.fk_approved_by = approved_by.employee_id
                LEFT JOIN employee_search_view as certified_avail ON supplemental_ppmp.fk_certified_funds_available_by = certified_avail.employee_id
                ORDER BY fnl.stock_activity 
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
        echo "m231129_062758_update_supplemental_ppmp_index_view cannot be reverted.\n";

        return false;
    }
    */
}
