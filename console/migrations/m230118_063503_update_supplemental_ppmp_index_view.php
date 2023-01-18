<?php

use yii\db\Migration;

/**
 * Class m230118_063503_update_supplemental_ppmp_index_view
 */
class m230118_063503_update_supplemental_ppmp_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS supplemental_ppmp_index;
        CREATE VIEW supplemental_ppmp_index as SELECT 
        supplemental_ppmp.id,
        supplemental_ppmp.budget_year,
        supplemental_ppmp.cse_type,
        supplemental_ppmp.serial_number,
        office.office_name,
        divisions.division,
        division_program_unit.`name` as division_program_unit_name,
        supplemental_ppmp_non_cse.activity_name,
        prepared_by.employee_name as prepared_by,
        reviewed_by.employee_name as reviewed_by,
        approved_by.employee_name as approved_by,
        certified_avail.employee_name as certified_avail,
        ttl_amt.total as total_amount,
        0 as ttl_qty,
                    IFNULL(    ttl_amt.total,0) - IFNULL(ttl_pr.total_pr_amt,0 ) as balance,
                    0 bal_qty
        FROM supplemental_ppmp
        INNER JOIN supplemental_ppmp_non_cse ON supplemental_ppmp.id = supplemental_ppmp_non_cse.fk_supplemental_ppmp_id

        LEFT JOIN office ON supplemental_ppmp.fk_office_id = office.id
        LEFT JOIN divisions ON supplemental_ppmp.fk_division_id = divisions.id
        LEFT JOIN division_program_unit ON supplemental_ppmp.fk_division_program_unit_id = division_program_unit.id
        LEFT JOIN employee_search_view as prepared_by ON supplemental_ppmp.fk_prepared_by = prepared_by.employee_id
        LEFT JOIN employee_search_view as reviewed_by ON supplemental_ppmp.fk_reviewed_by = reviewed_by.employee_id
        LEFT JOIN employee_search_view as approved_by ON supplemental_ppmp.fk_approved_by = approved_by.employee_id
        LEFT JOIN employee_search_view as certified_avail ON supplemental_ppmp.fk_certified_funds_available_by = certified_avail.employee_id
        LEFT JOIN ( 
                        SELECT supplemental_ppmp_non_cse_items.fk_supplemental_ppmp_non_cse_id,
                        SUM(supplemental_ppmp_non_cse_items.amount) as total
                        FROM supplemental_ppmp_non_cse_items
                        WHERE supplemental_ppmp_non_cse_items.is_deleted = 0
                        GROUP BY supplemental_ppmp_non_cse_items.fk_supplemental_ppmp_non_cse_id
                        ) as ttl_amt ON supplemental_ppmp_non_cse.id = ttl_amt.fk_supplemental_ppmp_non_cse_id
                    LEFT JOIN (SELECT 
            pr_purchase_request.fk_supplemental_ppmp_noncse_id,
            SUM(pr_purchase_request_item.quantity) as total_pr_qty,
            SUM(pr_purchase_request_item.unit_cost *pr_purchase_request_item.quantity)as total_pr_amt
            FROM pr_purchase_request 
            LEFT JOIN pr_purchase_request_item ON pr_purchase_request.id = pr_purchase_request_item.pr_purchase_request_id
                            WHERE pr_purchase_request_item.is_deleted = 0
            GROUP BY pr_purchase_request.fk_supplemental_ppmp_noncse_id  ) 
                            as ttl_pr ON supplemental_ppmp_non_cse.id = ttl_pr.fk_supplemental_ppmp_noncse_id
        WHERE supplemental_ppmp_non_cse.is_deleted = 0
AND
supplemental_ppmp_non_cse.type='activity'

UNION ALL 

SELECT
supplemental_ppmp.id,
        supplemental_ppmp.budget_year,
        supplemental_ppmp.cse_type,
        supplemental_ppmp.serial_number,
        office.office_name,
        divisions.division,
        division_program_unit.`name` as division_program_unit_name,
        CONCAT(supplemental_ppmp_non_cse.activity_name,pr_stock.stock_title) as activity_name,
        prepared_by.employee_name as prepared_by,
        reviewed_by.employee_name as reviewed_by,
        approved_by.employee_name as approved_by,
        certified_avail.employee_name as certified_avail,
        supplemental_ppmp_non_cse_items.amount as total_amount,
        supplemental_ppmp_non_cse_items.quantity as ttl_qty,
            supplemental_ppmp_non_cse_items.amount - IFNULL(ttl_pr.ttl_pr	,0) as balance,
                    '' bal_qty

FROM
supplemental_ppmp_non_cse_items
INNER JOIN supplemental_ppmp_non_cse ON supplemental_ppmp_non_cse_items.fk_supplemental_ppmp_non_cse_id = supplemental_ppmp_non_cse.id
INNER JOIN supplemental_ppmp ON supplemental_ppmp_non_cse.fk_supplemental_ppmp_id = supplemental_ppmp.id
LEFT JOIN office ON supplemental_ppmp.fk_office_id = office.id
        LEFT JOIN divisions ON supplemental_ppmp.fk_division_id = divisions.id
        LEFT JOIN division_program_unit ON supplemental_ppmp.fk_division_program_unit_id = division_program_unit.id
        LEFT JOIN employee_search_view as prepared_by ON supplemental_ppmp.fk_prepared_by = prepared_by.employee_id
        LEFT JOIN employee_search_view as reviewed_by ON supplemental_ppmp.fk_reviewed_by = reviewed_by.employee_id
        LEFT JOIN employee_search_view as approved_by ON supplemental_ppmp.fk_approved_by = approved_by.employee_id
        LEFT JOIN employee_search_view as certified_avail ON supplemental_ppmp.fk_certified_funds_available_by = certified_avail.employee_id
LEFT JOIN pr_stock ON supplemental_ppmp_non_cse_items.fk_pr_stock_id = pr_stock.id
    LEFT JOIN (SELECT 
pr_purchase_request_item.fk_ppmp_non_cse_item_id,
SUM( pr_purchase_request_item.unit_cost * pr_purchase_request_item.quantity) as ttl_pr
FROM pr_purchase_request_item 
GROUP BY pr_purchase_request_item.fk_ppmp_non_cse_item_id ) 
                            as ttl_pr ON supplemental_ppmp_non_cse_items.id = ttl_pr.fk_ppmp_non_cse_item_id


WHERE supplemental_ppmp_non_cse_items.is_deleted  = 0
AND supplemental_ppmp_non_cse.is_deleted = 0
AND supplemental_ppmp_non_cse.type = 'fixed expenses'
        UNION ALL
        SELECT 
        supplemental_ppmp.id,
        supplemental_ppmp.budget_year,
        supplemental_ppmp.cse_type,
        supplemental_ppmp.serial_number,
        office.office_name,
        divisions.division,
        division_program_unit.`name` as division_program_unit_name,
        pr_stock.stock_title,
        prepared_by.employee_name as prepared_by,
        reviewed_by.employee_name as reviewed_by,
        approved_by.employee_name as approved_by,
        certified_avail.employee_name as certified_avail,
        supplemental_ppmp_cse.amount,

        IFNULL(supplemental_ppmp_cse.jan_qty,0)+
        IFNULL(supplemental_ppmp_cse.feb_qty,0)+
        IFNULL(supplemental_ppmp_cse.mar_qty,0)+
        IFNULL(supplemental_ppmp_cse.apr_qty,0)+
        IFNULL(supplemental_ppmp_cse.may_qty,0)+
        IFNULL(supplemental_ppmp_cse.jun_qty,0)+
        IFNULL(supplemental_ppmp_cse.jul_qty,0)+
        IFNULL(supplemental_ppmp_cse.aug_qty,0)+
        IFNULL(supplemental_ppmp_cse.sep_qty,0)+
        IFNULL(supplemental_ppmp_cse.oct_qty,0)+
        IFNULL(supplemental_ppmp_cse.nov_qty,0)+
        IFNULL(supplemental_ppmp_cse.dec_qty,0) as ttl_qqy,
                    IFNULL(   supplemental_ppmp_cse.amount,0) - IFNULL(ttl_pr.ttl_cost,0) as balance,
                    ttl_pr.ttl_qty

        FROM supplemental_ppmp
        INNER JOIN supplemental_ppmp_cse ON supplemental_ppmp.id = supplemental_ppmp_cse.fk_supplemental_ppmp_id
        LEFT JOIN pr_stock ON supplemental_ppmp_cse.fk_pr_stock_id = pr_stock.id
        LEFT JOIN office ON supplemental_ppmp.fk_office_id = office.id
        LEFT JOIN divisions ON supplemental_ppmp.fk_division_id = divisions.id
        LEFT JOIN division_program_unit ON supplemental_ppmp.fk_division_program_unit_id = division_program_unit.id
        LEFT JOIN employee_search_view as prepared_by ON supplemental_ppmp.fk_prepared_by = prepared_by.employee_id
        LEFT JOIN employee_search_view as reviewed_by ON supplemental_ppmp.fk_reviewed_by = reviewed_by.employee_id
        LEFT JOIN employee_search_view as approved_by ON supplemental_ppmp.fk_approved_by = approved_by.employee_id
        LEFT JOIN employee_search_view as certified_avail ON supplemental_ppmp.fk_certified_funds_available_by = certified_avail.employee_id
                    LEFT JOIN (SELECT 
                         pr_purchase_request.fk_supplemental_ppmp_cse_id,
                         SUM(pr_purchase_request_item.unit_cost*pr_purchase_request_item.quantity) as ttl_cost,
                            SUM(pr_purchase_request_item.quantity) as ttl_qty
                        FROM pr_purchase_request
                        LEFT JOIN pr_purchase_request_item ON pr_purchase_request.id = pr_purchase_request_item.pr_purchase_request_id
                        WHERE 
                        pr_purchase_request_item.is_deleted = 0
                        GROUP BY  pr_purchase_request.fk_supplemental_ppmp_cse_id) 
                        as ttl_pr ON supplemental_ppmp_cse.id = ttl_pr.fk_supplemental_ppmp_cse_id
        WHERE supplemental_ppmp_cse.is_deleted = 0 ")
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
        echo "m230118_063503_update_supplemental_ppmp_index_view cannot be reverted.\n";

        return false;
    }
    */
}
