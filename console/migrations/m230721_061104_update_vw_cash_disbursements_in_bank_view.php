<?php

use yii\db\Migration;

/**
 * Class m230721_061104_update_vw_cash_disbursements_in_bank_view
 */
class m230721_061104_update_vw_cash_disbursements_in_bank_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS vw_cash_disbursements_in_bank;
        CREATE VIEW  vw_cash_disbursements_in_bank AS 
        WITH         checkTtlAmt as (
        
            SELECT 
            cash_disbursement_items.fk_cash_disbursement_id,
            SUM(dv_aucs_index.ttlAmtDisbursed) as ttlDisbursed,
            SUM(COALESCE(dv_aucs_index.ttlTax,0)) as ttlTax
            FROM cash_disbursement_items
            JOIN dv_aucs_index ON cash_disbursement_items.fk_dv_aucs_id = dv_aucs_index.id
            WHERE 
            cash_disbursement_items.is_deleted = 0
            GROUP BY cash_disbursement_items.fk_cash_disbursement_id
            )
    SELECT 
        cash_disbursement.id,
        cash_disbursement.reporting_period,
        cash_disbursement.ada_number,
        cash_disbursement.check_or_ada_no,
        cash_disbursement.issuance_date,
        books.`name` as book_name,
        mode_of_payments.`name` as mode_name,
     checkTtlAmt.ttlDisbursed,
            checkTtlAmt.ttlTax
    
        FROM acic_in_bank_items
        JOIN acics ON acic_in_bank_items.fk_acic_id = acics.id
        JOIN acics_cash_items ON acics.id=  acics_cash_items.fk_acic_id
        JOIN cash_disbursement ON acics_cash_items.fk_cash_disbursement_id = cash_disbursement.id
        LEFT JOIN books ON cash_disbursement.book_id = books.id
        LEFT JOIN mode_of_payments ON cash_disbursement.fk_mode_of_payment_id  = mode_of_payments.id
      LEFT JOIN checkTtlAmt ON cash_disbursement.id  = checkTtlAmt.fk_cash_disbursement_id
        WHERE 
        acic_in_bank_items.is_deleted = 0
        AND acics_cash_items.is_deleted = 0
        AND NOT EXISTS (SELECT cncl_chks.parent_disbursement
            FROM cash_disbursement as cncl_chks 
            WHERE cncl_chks.parent_disbursement = cash_disbursement.id 
            AND cncl_chks.is_cancelled = 1 
            AND cncl_chks.parent_disbursement IS NOT NULL
            ) ")
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
        echo "m230721_061104_update_vw_cash_disbursements_in_bank_view cannot be reverted.\n";

        return false;
    }
    */
}
