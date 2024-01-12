<?php

use yii\db\Migration;

/**
 * Class m240112_025015_update_purchase_orders_for_rfi_view
 */
class m240112_025015_update_purchase_orders_for_rfi_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
        DROP VIEW IF EXISTS purchase_orders_for_rfi;
        CREATE VIEW purchase_orders_for_rfi as 
        
            SELECT 
            pr_purchase_order_items_aoq_items.id as po_aoq_item_id,
            pr_purchase_order_item.serial_number as po_number,
            pr_purchase_request.purpose as project_title,
            requested_by.employee_name as pr_requested_by,
            pr_purchase_request.purpose,
            pr_stock.stock_title,
            REPLACE(pr_purchase_request_item.specification,'[n]',' ') as specification,
            unit_of_measure.unit_of_measure,
                --             (CASE 
                --                 WHEN   unit_of_measure.unit_of_measure ='lot' THEN pr_purchase_request_item.quantity
                --                 ELSE  pr_purchase_request_item.quantity - IFNULL(aoq_items_quantity.quantity,0)
                --             END) as quantity,

            pr_purchase_request_item.quantity - IFNULL(aoq_items_quantity.quantity,0) as quantity,
            pr_aoq_entries.amount as unit_cost,
            payee.account_name as payee,
            divisions.division,
            UPPER(CONCAT(office.office_name,'-',divisions.division)) as unit,
            office.office_name
            FROM pr_purchase_request
            JOIN pr_purchase_request_item ON pr_purchase_request.id = pr_purchase_request_item.pr_purchase_request_id
            JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
            JOIN unit_of_measure ON pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id
            JOIN pr_rfq_item ON pr_purchase_request_item.id = pr_rfq_item.pr_purchase_request_item_id
            JOIN pr_aoq_entries ON pr_rfq_item.id = pr_aoq_entries.pr_rfq_item_id
            JOIN payee ON pr_aoq_entries.payee_id = payee.id
            JOIN pr_aoq ON pr_aoq_entries.pr_aoq_id = pr_aoq.id
            JOIN pr_purchase_order_items_aoq_items ON pr_aoq_entries.id = pr_purchase_order_items_aoq_items.fk_aoq_entries_id
            JOIN pr_purchase_order_item ON pr_purchase_order_items_aoq_items.fk_purchase_order_item_id = pr_purchase_order_item.id
            JOIN pr_purchase_order ON pr_purchase_order_item.fk_pr_purchase_order_id = pr_purchase_order.id
            LEFT JOIN employee_search_view as requested_by ON pr_purchase_request.requested_by_id = requested_by.employee_id
            LEFT JOIN office ON pr_purchase_request.fk_office_id = office.id
            LEFT JOIN divisions ON pr_purchase_request.fk_division_id = divisions.id
            LEFT JOIN (SELECT 
            request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id,
            SUM(request_for_inspection_items.quantity) as quantity
            FROM request_for_inspection_items 	
            WHERE request_for_inspection_items.is_deleted !=1
            GROUP BY request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id) 
            as aoq_items_quantity ON pr_purchase_order_items_aoq_items.id = aoq_items_quantity.fk_pr_purchase_order_items_aoq_item_id
            WHERE pr_aoq_entries.is_lowest = 1 
            AND pr_purchase_order.is_cancelled = 0
            AND pr_purchase_order_item.is_cancelled = 0

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
        echo "m240112_025015_update_purchase_orders_for_rfi_view cannot be reverted.\n";

        return false;
    }
    */
}
