<?php

use yii\db\Migration;

/**
 * Class m240202_003745_update_vw_purchase_order_index_view
 */
class m240202_003745_update_vw_purchase_order_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<< SQL
            DROP VIEW IF EXISTS vw_purchase_order_index;
            CREATE VIEW vw_purchase_order_index as 
                SELECT 
                    pr_purchase_order.id,
                    pr_purchase_order_item.serial_number as po_number,
                                    payee.registered_name as payee_name,
                    pr_purchase_request.purpose,
                    divisions.division,
                    office.office_name,
                    pr_mode_of_procurement.mode_name as mode_of_procurement_name,
                    pr_purchase_order.created_at,
                    (CASE 
                        WHEN pr_purchase_order.is_cancelled = 1 THEN pr_purchase_order.is_cancelled
                        ELSE pr_purchase_order_item.is_cancelled
                    END) as is_cancelled
                                                                                                    
                FROM pr_purchase_order
                JOIN pr_purchase_order_item ON pr_purchase_order.id = pr_purchase_order_item.fk_pr_purchase_order_id
                JOIN pr_purchase_order_items_aoq_items ON pr_purchase_order_item.id = pr_purchase_order_items_aoq_items.fk_purchase_order_item_id
                JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id = pr_aoq_entries.id
                JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
                JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
                JOIN pr_purchase_request ON pr_purchase_request_item.pr_purchase_request_id =pr_purchase_request.id     
                LEFT JOIN office ON pr_purchase_order.fk_office_id = office.id
                LEFT JOIN divisions ON pr_purchase_request.fk_division_id = divisions.id
                LEFT JOIN pr_mode_of_procurement ON pr_purchase_order.fk_mode_of_procurement_id = pr_mode_of_procurement.id
                            LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
                            WHERE pr_aoq_entries.is_deleted = 0
                GROUP BY pr_purchase_order.id,
                    pr_purchase_order_item.serial_number ,
                                    payee.registered_name,
                    pr_purchase_request.purpose,
                    divisions.division,
                    office.office_name,
                    pr_mode_of_procurement.mode_name ,
                    pr_purchase_order.created_at,
                    (CASE 
                        WHEN pr_purchase_order.is_cancelled = 1 THEN pr_purchase_order.is_cancelled
                        ELSE pr_purchase_order_item.is_cancelled
                    END) 
                ORDER BY  pr_purchase_order.po_date DESC 
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
        echo "m240202_003745_update_vw_purchase_order_index_view cannot be reverted.\n";

        return false;
    }
    */
}
