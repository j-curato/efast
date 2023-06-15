<?php

use yii\db\Migration;

/**
 * Class m230516_030328_create_vw_undisburesedDvs_view
 */
class m230516_030328_create_vw_undisburesedDvs_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        YIi::$app->db->createCommand("
        DROP VIEW IF EXISTS vw_undisbursed_dvs;
        CREATE VIEW vw_undisbursed_dvs AS 
        WITH cte_CancelledChecks as (SELECT cash_disbursement.parent_disbursement FROM cash_disbursement
        WHERE 
        is_cancelled    = 1
        AND parent_disbursement IS NOT NULL
        ),
        cte_GoodChecks as (

        SELECT cash_disbursement_items.fk_dv_aucs_id FROM cash_disbursement
        LEFT JOIN cash_disbursement_items ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
        WHERE 
        NOT EXISTS(SELECT * FROM cte_CancelledChecks WHERE cte_CancelledChecks.parent_disbursement = cash_disbursement.id)
        AND cash_disbursement.is_cancelled = 0
        )
        SELECT dv_aucs_index.* FROM dv_aucs_index 
        LEFT JOIN cte_GoodChecks ON dv_aucs_index.id = cte_GoodChecks.fk_dv_aucs_id
        WHERE cte_GoodChecks.fk_dv_aucs_id IS NULL
        AND dv_aucs_index.is_cancelled = 0")
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
        echo "m230516_030328_create_vw_undisburesedDvs_view cannot be reverted.\n";

        return false;
    }
    */
}
