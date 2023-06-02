<?php

use yii\db\Migration;

/**
 * Class m230601_030207_update_vw_good_cash_disbursements_view
 */
class m230601_030207_update_vw_good_cash_disbursements_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
        DROP VIEW IF EXISTS vw_good_cash_disbursements;
        CREATE VIEW vw_good_cash_disbursements as 
        SELECT
        cash_disbursement.id,
        cash_disbursement.reporting_period,
        cash_disbursement.issuance_date,
        books.`name` as book_name,
        mode_of_payments.`name` as mode_name,
        cash_disbursement.check_or_ada_no,
        cash_disbursement.ada_number
        FROM cash_disbursement
        LEFT JOIN books ON cash_disbursement.book_id = books.id
        LEFT JOIN mode_of_payments ON cash_disbursement.fk_mode_of_payment_id  = mode_of_payments.id
        WHERE 
            cash_disbursement.is_cancelled = 0
            
            AND NOT EXISTS (SELECT cncl_chks.parent_disbursement
            FROM cash_disbursement as cncl_chks 
            WHERE cncl_chks.parent_disbursement = cash_disbursement.id 
            AND cncl_chks.is_cancelled = 1 
            AND cncl_chks.parent_disbursement IS NOT NULL
            ) 

            AND NOT EXISTS (
            SELECT 
            acics_cash_items.fk_cash_disbursement_id
            FROM acics_cash_items
            JOIN acics ON acics_cash_items.fk_acic_id = acics.id
            JOIN acic_in_bank_items ON acics.id = acic_in_bank_items.fk_acic_id
            WHERE 
            acics_cash_items.fk_cash_disbursement_id = cash_disbursement.id 
            AND acics_cash_items.is_deleted  = 0
            AND acic_in_bank_items.is_deleted   = 0
        )



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
        echo "m230601_030207_update_vw_good_cash_disbursements_view cannot be reverted.\n";

        return false;
    }
    */
}
