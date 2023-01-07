<?php

use yii\db\Migration;

/**
 * Class m230107_071502_create_purchase_request_index_view
 */
class m230107_071502_create_purchase_request_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS purchase_request_index;
        CREATE VIEW purchase_request_index as 
            SELECT 
            pr_purchase_request.id,
            pr_purchase_request.pr_number,
            office.office_name,
            UPPER(divisions.division) as division,
            division_program_unit.`name` as division_program_unit,
            pr_stock.stock_title  as activity_name,
            requested_by.employee_name as requested_by,
            approved_by.employee_name as approved_by,
            books.`name` as book_name,
            pr_purchase_request.purpose,
            pr_purchase_request.date
            FROM 
            pr_purchase_request
            INNER JOIN supplemental_ppmp_cse ON pr_purchase_request.fk_supplemental_ppmp_cse_id = supplemental_ppmp_cse.id
            INNER JOIN supplemental_ppmp ON supplemental_ppmp_cse.fk_supplemental_ppmp_id = supplemental_ppmp.id
            LEFT JOIN office ON supplemental_ppmp.fk_office_id = office.id
            LEFT JOIN division_program_unit ON supplemental_ppmp.fk_division_program_unit_id = division_program_unit.id
            LEFT JOIN divisions ON supplemental_ppmp.fk_division_id = divisions.id
            LEFT JOIN pr_stock ON supplemental_ppmp_cse.fk_pr_stock_id = pr_stock.id
            LEFT JOIN employee_search_view as requested_by ON pr_purchase_request.requested_by_id = requested_by.employee_id
            LEFT JOIN employee_search_view as approved_by ON pr_purchase_request.approved_by_id = approved_by.employee_id
            LEFT JOIN books ON pr_purchase_request.book_id = books.id
            UNION ALL
            SELECT 
            pr_purchase_request.id,
            pr_purchase_request.pr_number,
            office.office_name,
            UPPER(divisions.division) as division,
            division_program_unit.`name` as division_program_unit,
            supplemental_ppmp_non_cse.activity_name,
            requested_by.employee_name as requested_by,
            approved_by.employee_name as approved_by,
            books.`name` as book_name,
            pr_purchase_request.purpose,
            pr_purchase_request.date
            FROM 
            pr_purchase_request
            INNER JOIN supplemental_ppmp_non_cse ON pr_purchase_request.fk_supplemental_ppmp_noncse_id = supplemental_ppmp_non_cse.id
            INNER JOIN supplemental_ppmp ON supplemental_ppmp_non_cse.fk_supplemental_ppmp_id = supplemental_ppmp.id
            LEFT JOIN office ON supplemental_ppmp.fk_office_id = office.id
            LEFT JOIN division_program_unit ON supplemental_ppmp.fk_division_program_unit_id = division_program_unit.id
            LEFT JOIN divisions ON supplemental_ppmp.fk_division_id = divisions.id
            LEFT JOIN employee_search_view as requested_by ON pr_purchase_request.requested_by_id = requested_by.employee_id
            LEFT JOIN employee_search_view as approved_by ON pr_purchase_request.approved_by_id = approved_by.employee_id
            LEFT JOIN books ON pr_purchase_request.book_id = books.id
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
        echo "m230107_071502_create_purchase_request_index_view cannot be reverted.\n";

        return false;
    }
    */
}
