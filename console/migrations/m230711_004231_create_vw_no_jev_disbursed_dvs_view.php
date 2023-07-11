<?php

use yii\db\Migration;

/**
 * Class m230711_004231_create_vw_no_jev_disbursed_dvs_view
 */
class m230711_004231_create_vw_no_jev_disbursed_dvs_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS vw_no_jev_disbursed_dvs;
            CREATE VIEW vw_no_jev_disbursed_dvs as WITH cte_gd_checks as (
            SELECT
            `cash_disbursement_items`.`fk_dv_aucs_id`
            FROM `cash_disbursement` 
            JOIN `cash_disbursement_items` ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
            WHERE `cash_disbursement`.`is_cancelled`=0
            AND `cash_disbursement_items`.`is_deleted`=0
            AND NOT EXISTS (SELECT `c`.`parent_disbursement` 
            FROM `cash_disbursement` `c` 
            WHERE `c`.`is_cancelled`=1 AND `c`.`parent_disbursement`=cash_disbursement.id)
            )
            SELECT 
            `dv_aucs`.`id`, 
            `dv_aucs`.`dv_number` 
            FROM `dv_aucs` 
            WHERE 
            EXISTS (SELECT * FROM cte_gd_checks WHERE cte_gd_checks.fk_dv_aucs_id  = dv_aucs.id ) 
            AND NOT EXISTS (
            SELECT 
            jev_preparation.fk_dv_aucs_id
            FROM jev_preparation
            WHERE
            jev_preparation.fk_dv_aucs_id IS NOT NULL
            AND jev_preparation.fk_dv_aucs_id  = dv_aucs.id
            GROUP BY 
            `dv_aucs`.`id`, 
            `dv_aucs`.`dv_number` 
            )")
            ->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }

    /*`
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230711_004231_create_vw_no_jev_disbursed_dvs_view cannot be reverted.\n";

        return false;
    }
    */
}
