<?php

use yii\db\Migration;

/**
 * Class m220722_022249_create_pr_summary_view
 */
class m220722_022249_create_pr_summary_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP VIEW IF EXISTS pr_summary;
            CREATE VIEW pr_summary as SELECT 
                pr_purchase_order_item.serial_number as po_number,
                pr_aoq.aoq_number,
                pr_rfq.rfq_number,
                pr_purchase_request.pr_number,
                pr_summary.payee,
                pr_purchase_request.purpose,
                pr_purchase_order.id as po_id,
                pr_aoq.id as aoq_id,
                pr_rfq.id as rfq_id,
                pr_purchase_request.id as pr_id
                FROM
                (
                SELECT 
                pr_purchase_order_items_aoq_items.fk_purchase_order_item_id,
                pr_aoq.id as aoq_id,
                pr_rfq.id as rfq_id,
                pr_purchase_request.id as purchase_request_id,
                payee.account_name as payee

                FROM 
                pr_purchase_order_items_aoq_items
                LEFT JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id = pr_aoq_entries.id
                LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
                LEFT JOIN pr_aoq ON pr_aoq_entries.pr_aoq_id = pr_aoq.id
                LEFT JOIN pr_rfq ON pr_aoq.pr_rfq_id = pr_rfq.id
                LEFT JOIN pr_purchase_request ON pr_rfq.pr_purchase_request_id = pr_purchase_request.id
                GROUP BY
                pr_purchase_order_items_aoq_items.fk_purchase_order_item_id,
                pr_aoq.id,
                pr_rfq.id,
                pr_purchase_request.id,
                payee.account_name) as pr_summary
                LEFT JOIN pr_purchase_order_item ON pr_summary.fk_purchase_order_item_id = pr_purchase_order_item.id
                LEFT JOIN pr_purchase_order ON pr_purchase_order_item.fk_pr_purchase_order_id  = pr_purchase_order.id
                LEFT JOIN pr_aoq ON pr_summary.aoq_id = pr_aoq.id
                LEFT JOIN pr_rfq ON pr_summary.rfq_id = pr_rfq.id
                LEFT JOIN pr_purchase_request ON pr_summary.purchase_request_id = pr_purchase_request.id
                ORDER BY pr_purchase_request.pr_number 
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
        echo "m220722_022249_create_pr_summary_view cannot be reverted.\n";

        return false;
    }
    */
}
