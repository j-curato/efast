<?php

use yii\db\Migration;

/**
 * Class m230817_011433_update_iar_index_view
 */
class m230817_011433_update_iar_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
                DROP VIEW IF EXISTS iar_index;
                CREATE VIEW iar_index as 
                    SELECT 
                    iar.id,
                    iar.iar_number,
                    inspection_report.ir_number,
                    ir_details.rfi_number,
                    ir_details.po_number,
                    ir_details.purpose,
                    end_user.employee_name as end_user,
                    ir_details.requested_by_name,
                    ir_details.inspector_name,
                    ir_details.payee_name,
                    ir_details.payee_id,
                    ir_details.total_amount,
                    ir_details.office_name,
                    ir_details.division
                    FROM iar
                    LEFT JOIN inspection_report ON iar.fk_ir_id = inspection_report.id
                    LEFT JOIN employee_search_view as end_user ON inspection_report.fk_end_user = end_user.employee_id
                    INNER JOIN (SELECT 
                        inspection_report_items.fk_inspection_report_id,
                        requested_by.employee_name as requested_by_name,
                        inspector.employee_name as inspector_name,
                        pr_purchase_order_item.serial_number as po_number,
                        payee.account_name as payee_name,
                        payee.id  as payee_id,
                        SUM(pr_aoq_entries.amount) as total_amount,
                        pr_purchase_request.purpose,
                        request_for_inspection.rfi_number,
                        office.office_name,
                        divisions.division
                    FROM inspection_report_items 
                    INNER JOIN request_for_inspection_items ON inspection_report_items.fk_request_for_inspection_item_id = request_for_inspection_items.id
                    INNER JOIN request_for_inspection ON request_for_inspection_items.fk_request_for_inspection_id = request_for_inspection.id
                    INNER JOIN pr_purchase_order_items_aoq_items ON request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id = pr_purchase_order_items_aoq_items.id
                    INNER JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id = pr_aoq_entries.id
                    INNER JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
                    INNER JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
                    INNER JOIN pr_purchase_request ON pr_purchase_request_item.pr_purchase_request_id = pr_purchase_request.id
                    LEFT JOIN employee_search_view as requested_by ON request_for_inspection.fk_requested_by = requested_by.employee_id
                    LEFT JOIN employee_search_view as inspector ON request_for_inspection.fk_inspector = inspector.employee_id
                    LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
                    LEFT JOIN responsibility_center ON request_for_inspection.fk_responsibility_center_id  = responsibility_center.id
                    LEFT JOIN pr_purchase_order_item ON pr_purchase_order_items_aoq_items.fk_purchase_order_item_id = pr_purchase_order_item.id
                    LEFT JOIN office ON request_for_inspection.fk_office_id = office.id
                    LEFT JOIN divisions ON request_for_inspection.fk_division_id = divisions.id
                    
                    GROUP BY
                    inspection_report_items.fk_inspection_report_id,
                    requested_by.employee_name ,
                    inspector.employee_name ,
                    office.office_name,
                            divisions.division,
                    pr_purchase_order_item.serial_number ,
                    payee.account_name ,
                    pr_purchase_request.purpose,
                    request_for_inspection.rfi_number) as ir_details ON inspection_report.id = ir_details.fk_inspection_report_id
                    UNION
                    SELECT 
                    iar.id,
                    iar.iar_number,
                    inspection_report.ir_number,
                    ir_no_po_details.rfi_number,
                    ir_no_po_details.transaction_type,
                    ir_no_po_details.purpose,
                    end_user.employee_name as end_user,
                    ir_no_po_details.requested_by_name,
                    ir_no_po_details.inspected_by_name,
                    ir_no_po_details.payee_name,
                    ir_no_po_details.payee_id,
                    ir_no_po_details.total_amount,
                    ir_no_po_details.office_name,
                    ir_no_po_details.division
                    FROM iar
                    LEFT JOIN inspection_report ON iar.fk_ir_id = inspection_report.id
                    LEFT JOIN employee_search_view as end_user ON inspection_report.fk_end_user  = end_user.employee_id
                    INNER JOIN 
                    (
                    SELECT 
                        inspection_report_no_po_items.fk_inspection_report_id,
                        request_for_inspection.rfi_number,
                        rfi_without_po_items.project_name as purpose,
                        payee.account_name as payee_name,
                        payee.id as payee_id,
                        requested_by.employee_name as requested_by_name,
                        inspected_by.employee_name as inspected_by_name,
                        request_for_inspection.transaction_type,
                        SUM(rfi_without_po_items.unit_cost * rfi_without_po_items.quantity) as total_amount,
                        office.office_name,
                        divisions.division
                    FROM inspection_report_no_po_items 
                    INNER JOIN rfi_without_po_items ON inspection_report_no_po_items.fk_rfi_without_po_item_id = rfi_without_po_items.id
                    INNER JOIN request_for_inspection ON rfi_without_po_items.fk_request_for_inspection_id = request_for_inspection.id
                    INNER JOIN payee ON rfi_without_po_items.fk_payee_id  = payee.id
                    LEFT JOIN employee_search_view as requested_by ON request_for_inspection.fk_requested_by = requested_by.employee_id
                    LEFT JOIN employee_search_view as inspected_by ON request_for_inspection.fk_inspector = inspected_by.employee_id
                    LEFT JOIN office ON request_for_inspection.fk_office_id = office.id
                    LEFT JOIN divisions ON request_for_inspection.fk_division_id = divisions.id
                    
                    GROUP BY
                    inspection_report_no_po_items.fk_inspection_report_id,
                    request_for_inspection.rfi_number,
                    rfi_without_po_items.project_name ,
                    payee.account_name ,
                    requested_by.employee_name ,
                    office.office_name,
                    divisions.division,
                    request_for_inspection.transaction_type) as ir_no_po_details ON inspection_report.id = ir_no_po_details.fk_inspection_report_id 
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
        echo "m230817_011433_update_iar_index_view cannot be reverted.\n";

        return false;
    }
    */
}
