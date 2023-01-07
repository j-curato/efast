<?php

use yii\db\Migration;

/**
 * Class m230105_021930_create_puchase_request_ppmp_search_view
 */
class m230105_021930_create_puchase_request_ppmp_search_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS pr_ppmp_search_view;
            CREATE VIEW pr_ppmp_search_view AS 
                SELECT CONCAT(supplemental_ppmp_cse.id,'-cse') as id,pr_stock.stock_title as stock_or_act_name ,
                supplemental_ppmp.budget_year,
                supplemental_ppmp.fk_office_id,
                supplemental_ppmp.fk_division_id
                FROM supplemental_ppmp_cse
                LEFT JOIN pr_stock ON supplemental_ppmp_cse.fk_pr_stock_id = pr_stock.id
                LEFT JOIN supplemental_ppmp ON supplemental_ppmp_cse.fk_supplemental_ppmp_id = supplemental_ppmp.id
                UNION
                SELECT 
                CONCAT(supplemental_ppmp_non_cse.id,'-non_cse') as id,
                supplemental_ppmp_non_cse.activity_name,
                supplemental_ppmp.budget_year,
                supplemental_ppmp.fk_office_id,
                supplemental_ppmp.fk_division_id
                FROM supplemental_ppmp_non_cse
                LEFT JOIN supplemental_ppmp ON supplemental_ppmp_non_cse.fk_supplemental_ppmp_id = supplemental_ppmp.id
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
        echo "m230105_021930_create_puchase_request_ppmp_search_view cannot be reverted.\n";

        return false;
    }
    */
}
