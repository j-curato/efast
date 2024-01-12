<?php

use yii\db\Migration;

/**
 * Class m240112_015103_create_vw_purchase_order_index_view
 */
class m240112_015103_create_vw_purchase_order_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP VIEW IF EXISTS vw_purchase_order_index;
            CREATE VIEW vw_purchase_order_index AS 
                    
                SELECT 
                    pr_purchase_order.id,
                    pr_purchase_order.po_number,
                    pr_purchase_request.purpose,
                    divisions.division,
                    office.office_name,
                    pr_purchase_order.is_cancelled,
                    pr_mode_of_procurement.mode_name as mode_of_procurement_name,
                    pr_purchase_order.created_at
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
                GROUP BY pr_purchase_order.po_number,
                pr_purchase_request.purpose,
                divisions.division,
                office.office_name,
                pr_purchase_order.is_cancelled,
                pr_mode_of_procurement.mode_name,
                pr_purchase_order.created_at
                ORDER BY  pr_purchase_order.po_number DESC
                
        SQL;
        $this->execute($sql) ;
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
        echo "m240112_015103_create_vw_purchase_order_index_view cannot be reverted.\n";

        return false;
    }
    */
}
