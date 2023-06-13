<?php

use yii\db\Migration;

/**
 * Class m230613_073332_update_vw_gd_no_acic_chks_view
 */
class m230613_073332_update_vw_gd_no_acic_chks_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS vw_gd_no_acic_chks;
CREATE VIEW vw_gd_no_acic_chks as WITH cte_CancelledChecks as (SELECT cash_disbursement.parent_disbursement FROM cash_disbursement
WHERE 
is_cancelled    = 1
AND parent_disbursement IS NOT NULL
),
checkTtlAmt as (
SELECT 
cash_disbursement_items.fk_cash_disbursement_id,
SUM(dv_aucs_index.grossAmt) as ttl
FROM cash_disbursement_items
JOIN dv_aucs_index ON cash_disbursement_items.fk_dv_aucs_id = dv_aucs_index.id
WHERE 
cash_disbursement_items.is_deleted = 0
GROUP BY cash_disbursement_items.fk_cash_disbursement_id
)
SELECT 
cash_disbursement.id,
cash_disbursement.check_or_ada_no,
cash_disbursement.ada_number,
cash_disbursement.issuance_date,
books.`name` as book_name,
cash_disbursement.reporting_period,
mode_of_payments.`name` as mode_name,
checkTtlAmt.ttl

FROM cash_disbursement
LEFT JOIN books ON cash_disbursement.book_id = books.id
LEFT JOIN mode_of_payments ON cash_disbursement.fk_mode_of_payment_id = mode_of_payments.id
LEFT JOIN checkTtlAmt ON cash_disbursement.id  = checkTtlAmt.fk_cash_disbursement_id
WHERE 
NOT EXISTS(SELECT * FROM cte_CancelledChecks WHERE cte_CancelledChecks.parent_disbursement = cash_disbursement.id)
AND cash_disbursement.is_cancelled = 0
AND NOT EXISTS (SELECT acics_cash_items.fk_cash_disbursement_id FROM acics_cash_items WHERE acics_cash_items.fk_cash_disbursement_id  = cash_disbursement.id
AND acics_cash_items.is_deleted = 0 )  ")
            ->query();
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
        echo "m230613_073332_update_vw_gd_no_acic_chks_view cannot be reverted.\n";

        return false;
    }
    */
}
