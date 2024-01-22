<?php

use yii\db\Migration;

/**
 * Class m240119_065820_update_purchase_order_for_transmittal_view
 */
class m240119_065820_update_purchase_order_for_transmittal_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP VIEW IF EXISTS purchase_order_for_transmittal;
            CREATE VIEW purchase_order_for_transmittal as 
                 SELECT
                pr_purchase_order_item.id,
                pr_purchase_order_item.serial_number,
                payee.account_name as payee,
                pr_purchase_request.purpose
                FROM pr_purchase_order_item
                JOIN pr_purchase_order ON pr_purchase_order_item.fk_pr_purchase_order_id = pr_purchase_order.id
                LEFT JOIN pr_purchase_order_items_aoq_items ON pr_purchase_order_item.id = pr_purchase_order_items_aoq_items.fk_purchase_order_item_id
                LEFT JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id = pr_aoq_entries.id
                LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
                LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
                LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
                LEFT JOIN pr_purchase_request ON pr_purchase_request_item.pr_purchase_request_id = pr_purchase_request.id

                WHERE
                NOT EXISTS (
                SELECT purchase_order_transmittal_items.fk_purchase_order_item_id 
                FROM purchase_order_transmittal_items 
                WHERE purchase_order_transmittal_items.fk_purchase_order_item_id = pr_purchase_order_item.id)
                AND pr_purchase_order_item.is_cancelled = 0
                AND pr_purchase_order.is_cancelled = 0
                GROUP BY 
                pr_purchase_order_item.id,
                pr_purchase_order_item.serial_number,
                payee.account_name,
                pr_purchase_request.purpose 
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
        echo "m240119_065820_update_purchase_order_for_transmittal_view cannot be reverted.\n";

        return false;
    }
    */
}
