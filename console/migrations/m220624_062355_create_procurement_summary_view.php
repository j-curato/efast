<?php

use yii\db\Migration;

/**
 * Class m220624_062355_create_procurement_summary_view
 */
class m220624_062355_create_procurement_summary_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
        DROP  VIEW IF EXISTS procurement_summary;
        CREATE VIEW procurement_summary as 
        SELECT 
            pr_project_procurement.title as project_title,
            prepared_by.employee_name as prepared_by,
            pr_purchase_request.created_at as pr_created_at,
            pr_purchase_request.pr_number,
            pr_purchase_request.date as pr_date,
            requested_by.employee_name as pr_requested_by,
            approved_by.employee_name as pr_approved_by,
            pr_purchase_request.purpose,
            pr_stock.stock_title,
            REPLACE(pr_purchase_request_item.specification,'[n]',' ') as specification,
            unit_of_measure.unit_of_measure,
            pr_purchase_request_item.quantity,
            pr_purchase_request_item.unit_cost,

            pr_rfq.created_at as rfq_created_at,
            pr_rfq.rfq_number,
            pr_rfq._date as rfq_date,
            pr_rfq.deadline as rfq_deadline,
            canvasser.employee_name as canvasser,
            pr_aoq.created_at as aoq_created_at,
            pr_aoq.aoq_number,
            pr_aoq.pr_date as aoq_date,
            pr_aoq_entries.amount supplier_bid_amount,
            IF(pr_aoq_entries.is_lowest=1,'true','false') as lowest,
            pr_aoq_entries.remark,
            payee.account_name as payee,
            pr_purchase_order.created_at as po_created_at,
            pr_purchase_order.po_number,
            pr_contract_type.contract_name as contract_type,
            pr_mode_of_procurement.mode_name as mode_of_procurement

            FROM pr_project_procurement 
            LEFT JOIN pr_purchase_request ON pr_project_procurement.id = pr_purchase_request.pr_project_procurement_id
            LEFT JOIN pr_purchase_request_item ON pr_purchase_request.id = pr_purchase_request_item.pr_purchase_request_id
            LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
            LEFT JOIN unit_of_measure ON pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id
            LEFT JOIN pr_rfq_item ON pr_purchase_request_item.id = pr_rfq_item.pr_purchase_request_item_id
            LEFT JOIN pr_rfq ON pr_rfq_item.pr_rfq_id = pr_rfq.id
            LEFT JOIN pr_aoq_entries ON pr_rfq_item.id = pr_aoq_entries.pr_rfq_item_id
            LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
            LEFT JOIN pr_aoq ON pr_aoq_entries.pr_aoq_id = pr_aoq.id
            LEFT JOIN pr_purchase_order ON pr_aoq.id = pr_purchase_order.fk_pr_aoq_id
            LEFT JOIN employee_search_view as requested_by ON pr_purchase_request.requested_by_id = requested_by.employee_id
            LEFT JOIN employee_search_view as prepared_by ON pr_project_procurement.employee_id = prepared_by.employee_id
            LEFT JOIN employee_search_view as approved_by ON pr_purchase_request.approved_by_id = approved_by.employee_id
            LEFT JOIN employee_search_view as canvasser ON pr_rfq.employee_id = canvasser.employee_id
            LEFT JOIN pr_contract_type ON pr_purchase_order.fk_contract_type_id = pr_contract_type.id
            LEFT JOIN pr_mode_of_procurement ON pr_purchase_order.fk_mode_of_procurement_id = pr_mode_of_procurement.id
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
        echo "m220624_062355_create_procurement_summary_view cannot be reverted.\n";

        return false;
    }
    */
}
