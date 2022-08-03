<?php

use yii\db\Migration;

/**
 * Class m220803_021810_update_purchase_order_for_rfi_view
 */
class m220803_021810_update_purchase_order_for_rfi_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
        DROP VIEW IF EXISTS purchase_orders_for_rfi ;
        CREATE VIEW purchase_orders_for_rfi as 
            SELECT 
            pr_purchase_order_item.id,
            pr_purchase_order_item.serial_number as po_number,
            pr_project_procurement.title as project_name,
            pr_purchase_order.po_date,
            payee.account_name as payee,
            pr_office.division,
            pr_office.unit
            FROM pr_purchase_order_item
            LEFT JOIN pr_purchase_order ON pr_purchase_order_item.fk_pr_purchase_order_id = pr_purchase_order.id
            LEFT JOIN pr_aoq ON pr_purchase_order.fk_pr_aoq_id = pr_aoq.id
            LEFT JOIN pr_rfq ON pr_aoq.pr_rfq_id = pr_rfq.id
            LEFT JOIN pr_purchase_request ON pr_rfq.pr_purchase_request_id = pr_purchase_request.id
            LEFT JOIN pr_project_procurement ON pr_purchase_request.pr_project_procurement_id = pr_project_procurement.id
            LEFT JOIN (SELECT
                pr_purchase_order_items_aoq_items.fk_purchase_order_item_id,
                pr_aoq_entries.payee_id
                FROM pr_purchase_order_items_aoq_items
                LEFT JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id = pr_aoq_entries.id

                GROUP BY 
                pr_purchase_order_items_aoq_items.fk_purchase_order_item_id,
                pr_aoq_entries.payee_id
                ) as po_aoq_items ON pr_purchase_order_item.id  = po_aoq_items.fk_purchase_order_item_id

    
            LEFT JOIN payee ON po_aoq_items.payee_id = payee.id
            LEFT JOIN pr_office ON pr_project_procurement.pr_office_id = pr_office.id
            WHERE 
            NOT EXISTS (SELECT id FROM request_for_inspection_items WHERE  request_for_inspection_items.is_deleted !=1 AND  request_for_inspection_items.fk_purchase_order_item_id = pr_purchase_order_item.id )
            ORDER BY pr_office.division 
        SQL;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220803_021810_update_purchase_order_for_rfi_view cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220803_021810_update_purchase_order_for_rfi_view cannot be reverted.\n";

        return false;
    }
    */
}
