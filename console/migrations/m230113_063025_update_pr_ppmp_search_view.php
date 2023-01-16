<?php

use yii\db\Migration;

/**
 * Class m230113_063025_update_pr_ppmp_search_view
 */
class m230113_063025_update_pr_ppmp_search_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS pr_ppmp_search_view;
CREATE VIEW pr_ppmp_search_view as SELECT CONCAT(supplemental_ppmp_cse.id,'-cse') as id,
UPPER(CONCAT(office.office_name,'-',divisions.division,'-',pr_stock.stock_title)) as stock_or_act_name ,
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
                UPPER(CONCAT(office.office_name,'-',divisions.division,'-',supplemental_ppmp_non_cse.activity_name)) as activity_name,
                supplemental_ppmp.budget_year,
                supplemental_ppmp.fk_office_id,
                supplemental_ppmp.fk_division_id
                FROM supplemental_ppmp_non_cse
                LEFT JOIN supplemental_ppmp ON supplemental_ppmp_non_cse.fk_supplemental_ppmp_id = supplemental_ppmp.id 
	LEFT JOIN  office ON supplemental_ppmp.fk_office_id = office.id
LEFT JOIN divisions ON supplemental_ppmp.fk_division_id = divisions.id
WHERE supplemental_ppmp_non_cse.type ='activity'
UNION ALL 
  SELECT 
                CONCAT(supplemental_ppmp_non_cse.id,'-non_cse','-',supplemental_ppmp_non_cse_items.id) as id,
                UPPER(CONCAT(office.office_name,'-',divisions.division,'-',supplemental_ppmp_non_cse.activity_name,'-',pr_stock.stock_title)) as stock,
                supplemental_ppmp.budget_year,
                supplemental_ppmp.fk_office_id,
                supplemental_ppmp.fk_division_id
                FROM supplemental_ppmp_non_cse
LEFT JOIN supplemental_ppmp_non_cse_items ON supplemental_ppmp_non_cse.id = supplemental_ppmp_non_cse_items.fk_supplemental_ppmp_non_cse_id
                LEFT JOIN supplemental_ppmp ON supplemental_ppmp_non_cse.fk_supplemental_ppmp_id = supplemental_ppmp.id 
	LEFT JOIN  office ON supplemental_ppmp.fk_office_id = office.id
LEFT JOIN divisions ON supplemental_ppmp.fk_division_id = divisions.id
LEFT JOIN pr_stock ON supplemental_ppmp_non_cse_items.fk_pr_stock_id = pr_stock.id

WHERE supplemental_ppmp_non_cse.type ='fixed expenses'  ")
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
        echo "m230113_063025_update_pr_ppmp_search_view cannot be reverted.\n";

        return false;
    }
    */
}
