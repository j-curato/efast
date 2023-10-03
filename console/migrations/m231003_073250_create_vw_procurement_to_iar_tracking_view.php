<?php

use yii\db\Migration;

/**
 * Class m231003_073250_create_vw_procurement_to_iar_tracking_view
 */
class m231003_073250_create_vw_procurement_to_iar_tracking_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP VIEW IF EXISTS vw_procurement_to_iar_tracking;
            CREATE VIEW  vw_procurement_to_iar_tracking as 
                WITH cte_rfqDetails as (SELECT 
                pr_rfq.rfq_number,
                pr_rfq._date as rfq_date,
                pr_rfq.deadline as rfq_deadline,
                (CASE 
                    WHEN pr_rfq.is_cancelled = 1 THEN 'Cancelled'
                    ELSE 'Good'
                END) as rfq_is_cancelled,
                pr_rfq_item.pr_purchase_request_item_id,
                pr_rfq_item.id as rfq_item_id
                FROM pr_rfq
                LEFT JOIN pr_rfq_item ON pr_rfq.id = pr_rfq_item.pr_rfq_id),
                cte_aoqToPoDetails as (SELECT 
                pr_aoq.aoq_number,
                (CASE 
                    WHEN pr_aoq.is_cancelled = 1 THEN 'Cancelled'
                    ELSE 'Good'
                END) as aoq_is_cancelled,
                payee.registered_name as payee_name,
                pr_aoq_entries.amount as bidAmount,
                pr_purchase_order_item.serial_number as po_number,

                (CASE 
                    WHEN pr_purchase_order.is_cancelled  = 1 THEN 'Cancelled'
                    ELSE 'Good'
                END) as po_is_cancelled,
                pr_aoq_entries.pr_rfq_item_id,
                pr_aoq_entries.id,
                pr_purchase_order_items_aoq_items.id as po_aoq_item_id
                FROM 
                pr_aoq
                JOIN pr_aoq_entries ON pr_aoq.id = pr_aoq_entries.pr_aoq_id
                LEFT JOIN pr_purchase_order_items_aoq_items ON pr_aoq_entries.id = pr_purchase_order_items_aoq_items.fk_aoq_entries_id
                LEFT JOIN pr_purchase_order_item ON pr_purchase_order_items_aoq_items.fk_purchase_order_item_id = pr_purchase_order_item.id
                LEFT JOIN pr_purchase_order ON pr_purchase_order_item.fk_pr_purchase_order_id = pr_purchase_order.id
                LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
                WHERE 
                pr_aoq_entries.is_deleted = 0
                AND pr_aoq_entries.is_lowest = 1),
                cte_rfiToIarDetails as (
                SELECT 
                request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id,
                request_for_inspection.rfi_number,
                request_for_inspection.`date` as rfi_date,
                request_for_inspection_items.`from`,
                request_for_inspection_items.`to`,
                request_for_inspection_items.quantity,
                inspection_report.ir_number,
                iar.iar_number

                FROM 
                request_for_inspection
                JOIN request_for_inspection_items ON request_for_inspection.id = request_for_inspection_items.fk_request_for_inspection_id
                LEFT JOIN inspection_report_items ON request_for_inspection_items.id = inspection_report_items.fk_request_for_inspection_item_id
                LEFT JOIN inspection_report ON inspection_report_items.fk_inspection_report_id = inspection_report.id
                LEFT JOIN iar ON inspection_report.id = iar.fk_ir_id
                WHERE request_for_inspection_items.is_deleted = 0)
                SELECT 
                office.office_name,
                divisions.division,
                pr_purchase_request.pr_number,
                pr_purchase_request.purpose,
                pr_purchase_request.date as pr_date,
                pr_stock.stock_title as stock_name,
                REPLACE(REPLACE(pr_purchase_request_item.specification,'[n]',' '),'<br>',' ')as specification,
                (CASE
                    WHEN pr_purchase_request.is_cancelled  =1 THEN 'Cancelled'
                    ELSE 'Good'
                END)as pr_is_cancelled,
                pr_purchase_request_item.quantity,
                pr_purchase_request_item.unit_cost,
                cte_rfqDetails.rfq_number,
                cte_rfqDetails.rfq_date,
                cte_rfqDetails.rfq_deadline,
                cte_rfqDetails.rfq_is_cancelled,
                cte_aoqToPoDetails.aoq_number,
                cte_aoqToPoDetails.aoq_is_cancelled,
                cte_aoqToPoDetails.payee_name,
                cte_aoqToPoDetails.bidAmount,
                pr_purchase_request_item.quantity * cte_aoqToPoDetails.bidAmount as bidGrossAmount,
                cte_aoqToPoDetails.po_number,
                cte_aoqToPoDetails.po_is_cancelled,
                cte_rfiToIarDetails.rfi_number,
                cte_rfiToIarDetails.`rfi_date` ,
                cte_rfiToIarDetails.`from` as  inspection_from,
                cte_rfiToIarDetails.`to` as inspection_to,
                cte_rfiToIarDetails.quantity as inspected_quantity,
                cte_rfiToIarDetails.ir_number,
                cte_rfiToIarDetails.iar_number
                FROM pr_purchase_request
                JOIN pr_purchase_request_item ON pr_purchase_request.id = pr_purchase_request_item.pr_purchase_request_id
                LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
                LEFT JOIN cte_rfqDetails ON pr_purchase_request_item.id = cte_rfqDetails.pr_purchase_request_item_id
                LEFT JOIN cte_aoqToPoDetails ON cte_rfqDetails.rfq_item_id = cte_aoqToPoDetails.pr_rfq_item_id
                LEFT JOIN cte_rfiToIarDetails ON cte_aoqToPoDetails.po_aoq_item_id = cte_rfiToIarDetails.fk_pr_purchase_order_items_aoq_item_id
                LEFT JOIN office ON  pr_purchase_request.fk_office_id = office.id
                LEFT JOIN divisions ON pr_purchase_request.fk_division_id = divisions.id
                WHERE 
                pr_purchase_request_item.is_deleted  = 0
                ORDER BY pr_purchase_request.pr_number;

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
        echo "m231003_073250_create_vw_procurement_to_iar_tracking_view cannot be reverted.\n";

        return false;
    }
    */
}
