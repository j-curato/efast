<?php

use yii\db\Migration;

/**
 * Class m231213_075421_update_purchase_request_index_view
 */
class m231213_075421_update_purchase_request_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP VIEW IF EXISTS purchase_request_index;
            CREATE VIEW purchase_request_index as 
             WITH  
                itemsTtl as (SELECT 
                pr_purchase_request_allotments.fk_purchase_request_id,
                SUM(pr_purchase_request_allotments.amount) as ttlCost
                FROM 
                pr_purchase_request_allotments
                WHERE 
                pr_purchase_request_allotments.is_deleted = 0
                GROUP BY 
                pr_purchase_request_allotments.fk_purchase_request_id
                ),
                forTransactionBal as (
                SELECT 
                itemsTtl.fk_purchase_request_id,
                IFNULL(itemsTtl.ttlCost,0) - IFNULL(ttlTransaction.ttl,0) as bal
                FROM  itemsTtl
                LEFT JOIN (SELECT  pr_purchase_request_allotments.fk_purchase_request_id,
                SUM(transaction_pr_items.amount)as ttl
                FROM transaction_pr_items
                        JOIN pr_purchase_request_allotments ON transaction_pr_items.fk_pr_allotment_id = pr_purchase_request_allotments.id
                WHERE transaction_pr_items.is_deleted = 0
                GROUP BY pr_purchase_request_allotments.fk_purchase_request_id) as ttlTransaction ON itemsTtl.fk_purchase_request_id = ttlTransaction.fk_purchase_request_id
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
                forTransactionBal.bal as forTransactionBal,
                (CASE
                WHEN pr_purchase_request.is_cancelled =  0 THEN 'Good'
                ELSE 'Cancelled'
                END ) as is_cancelled
                FROM 
                pr_purchase_request
                LEFT JOIN office ON pr_purchase_request.fk_office_id = office.id
                LEFT JOIN division_program_unit ON pr_purchase_request.fk_division_program_unit_id = division_program_unit.id
                LEFT JOIN divisions ON pr_purchase_request.fk_division_id = divisions.id
                LEFT JOIN employee_search_view as requested_by ON pr_purchase_request.requested_by_id = requested_by.employee_id
                LEFT JOIN employee_search_view as approved_by ON pr_purchase_request.approved_by_id = approved_by.employee_id
                LEFT JOIN books ON pr_purchase_request.book_id = books.id
                LEFT JOIN  itemsTtl ON pr_purchase_request.id = itemsTtl.fk_purchase_request_id
                LEFT JOIN forTransactionBal ON pr_purchase_request.id = forTransactionBal.fk_purchase_request_id 
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
        echo "m231213_075421_update_purchase_request_index_view cannot be reverted.\n";

        return false;
    }
    */
}
