<?php

use yii\db\Migration;

/**
 * Class m230130_023540_update_purchase_request_index_view
 */
class m230130_023540_update_purchase_request_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS purchase_request_index;
        CREATE VIEW purchase_request_index AS 
        WITH  
        itemsTtl as (
        SELECT 
        pr_purchase_request_item.pr_purchase_request_id,
        SUM(pr_purchase_request_item.unit_cost * pr_purchase_request_item.quantity) as ttlCost
        FROM 
        pr_purchase_request_item
        WHERE 
        pr_purchase_request_item.is_deleted = 0
        GROUP BY 
        pr_purchase_request_item.pr_purchase_request_id
        ),
        forTransactionBal as (
        SELECT 
        itemsTtl.pr_purchase_request_id,
        IFNULL(itemsTtl.ttlCost,0) - IFNULL(ttlTransaction.ttl,0) as bal
        FROM  itemsTtl
        LEFT JOIN (SELECT  transaction_pr_items.fk_pr_purchase_request_id,
        SUM(transaction_pr_items.amount)as ttl
        FROM transaction_pr_items
        WHERE transaction_pr_items.is_deleted = 0
        GROUP BY transaction_pr_items.fk_pr_purchase_request_id) as ttlTransaction ON itemsTtl.pr_purchase_request_id = ttlTransaction.fk_pr_purchase_request_id
        )
        SELECT 
        pr_purchase_request.id,
        pr_purchase_request.pr_number,
        office.office_name,
        UPPER(divisions.division) as division,
        division_program_unit.`name` as division_program_unit,
        requested_by.employee_name as requested_by,
        approved_by.employee_name as approved_by,
        books.`name` as book_name,
        pr_purchase_request.purpose,
        pr_purchase_request.date,
        itemsTtl.ttlCost,
        forTransactionBal.bal as forTransactionBal
        FROM 
        pr_purchase_request
        LEFT JOIN office ON pr_purchase_request.fk_office_id = office.id
        LEFT JOIN division_program_unit ON pr_purchase_request.fk_division_program_unit_id = division_program_unit.id
        LEFT JOIN divisions ON pr_purchase_request.fk_division_id = divisions.id
        LEFT JOIN employee_search_view as requested_by ON pr_purchase_request.requested_by_id = requested_by.employee_id
        LEFT JOIN employee_search_view as approved_by ON pr_purchase_request.approved_by_id = approved_by.employee_id
        LEFT JOIN books ON pr_purchase_request.book_id = books.id
        LEFT JOIN  itemsTtl ON pr_purchase_request.id = itemsTtl.pr_purchase_request_id
        LEFT JOIN forTransactionBal ON pr_purchase_request.id = forTransactionBal.pr_purchase_request_id
        ")
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
        echo "m230130_023540_update_purchase_request_index_view cannot be reverted.\n";

        return false;
    }
    */
}
