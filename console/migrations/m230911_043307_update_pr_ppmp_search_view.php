<?php

use yii\db\Migration;

/**
 * Class m230911_043307_update_pr_ppmp_search_view
 */
class m230911_043307_update_pr_ppmp_search_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP VIEW IF EXISTS pr_ppmp_search_view;
            CREATE VIEW pr_ppmp_search_view AS 
                SELECT CONCAT(supplemental_ppmp_cse.id,'-cse') as id,
                UPPER(CONCAT(supplemental_ppmp.serial_number,'-',pr_stock.stock_title)) as stock_or_act_name ,
                supplemental_ppmp.budget_year,
                supplemental_ppmp.fk_office_id,
                supplemental_ppmp.fk_division_id

                FROM supplemental_ppmp_cse
                LEFT JOIN pr_stock ON supplemental_ppmp_cse.fk_pr_stock_id = pr_stock.id
                LEFT JOIN supplemental_ppmp ON supplemental_ppmp_cse.fk_supplemental_ppmp_id = supplemental_ppmp.id
                LEFT JOIN  office ON supplemental_ppmp.fk_office_id = office.id
                LEFT JOIN divisions ON supplemental_ppmp.fk_division_id = divisions.id
                UNION
                SELECT 
                CONCAT(supplemental_ppmp_non_cse.id,'-non_cse') as id,
                UPPER(CONCAT(supplemental_ppmp.serial_number,'-',supplemental_ppmp_non_cse.activity_name)) as activity_name,
                supplemental_ppmp.budget_year,
                supplemental_ppmp.fk_office_id,
                supplemental_ppmp.fk_division_id

                FROM supplemental_ppmp_non_cse
                LEFT JOIN supplemental_ppmp ON supplemental_ppmp_non_cse.fk_supplemental_ppmp_id = supplemental_ppmp.id 
                LEFT JOIN  office ON supplemental_ppmp.fk_office_id = office.id
                LEFT JOIN divisions ON supplemental_ppmp.fk_division_id = divisions.id
                WHERE supplemental_ppmp_non_cse.type ='activity'
                UNION  
                SELECT 
                'fixed_expenses-fixed_expenses' as id,
                'Fixed Expenses' as stock,
                '2023' as budget_year,
                'fixed_expenses' as fk_office_id,
                'fixed_expenses' as fk_division_id
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
        echo "m230911_043307_update_pr_ppmp_search_view cannot be reverted.\n";

        return false;
    }
    */
}
