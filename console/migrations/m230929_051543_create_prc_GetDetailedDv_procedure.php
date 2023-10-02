<?php

use yii\db\Migration;

/**
 * Class m230929_051543_create_prc_GetDetailedDv_procedure
 */
class m230929_051543_create_prc_GetDetailedDv_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP PROCEDURE IF EXISTS prc_GetDetailedDvs;
            CREATE PROCEDURE prc_GetDetailedDvs (IN yr INT)
            BEGIN
                WITH cte_gd_cash as (
            SELECT
                    cash_disbursement_items.fk_dv_aucs_id,
                cash_disbursement.reporting_period,
                cash_disbursement.issuance_date,
                books.`name` as book_name,
                mode_of_payments.`name` as mode_name,
                cash_disbursement.check_or_ada_no,
                cash_disbursement.ada_number
                FROM cash_disbursement
                    JOIN cash_disbursement_items ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
                LEFT JOIN books ON cash_disbursement.book_id = books.id
                LEFT JOIN mode_of_payments ON cash_disbursement.fk_mode_of_payment_id  = mode_of_payments.id
                WHERE 
                    cash_disbursement.is_cancelled = 0
                    AND cash_disbursement_items.is_deleted  = 0
                    AND NOT EXISTS (SELECT cncl_chks.parent_disbursement
                    FROM cash_disbursement as cncl_chks 
                    WHERE cncl_chks.parent_disbursement = cash_disbursement.id 
                    AND cncl_chks.is_cancelled = 1 
                    AND cncl_chks.parent_disbursement IS NOT NULL
                    ) 


            )
            SELECT 
                cte_gd_cash.check_or_ada_no,
                cte_gd_cash.ada_number,
                disbursed.total_disbursed,
                dv_aucs.dv_number,
                dv_aucs.reporting_period,
                process_ors.serial_number as ors_number,
                ors_amount.total_ors as ors_amt,
                payee.account_name as payee,
                dv_aucs.particular,
                dv_aucs_entries.amount_disbursed,
                dv_aucs_entries.vat_nonvat,
                dv_aucs_entries.ewt_goods_services,
                dv_aucs_entries.compensation,
                dv_aucs_entries.other_trust_liabilities,
                nature_of_transaction.`name` as nature_of_transaction,
                mrd_classification.`name` as mrd_classification,
                dv_aucs.is_cancelled,
                IF(dv_aucs.is_payable=1,'Yes','No') as payable,
                books.`name` as book_name,
                dv_aucs.created_at
            FROM dv_aucs
            LEFT JOIN dv_aucs_entries ON dv_aucs.id = dv_aucs_entries.dv_aucs_id

            LEFT JOIN payee ON dv_aucs.payee_id = payee.id
            LEFT JOIN nature_of_transaction ON dv_aucs.nature_of_transaction_id = nature_of_transaction.id
            LEFT JOIN mrd_classification ON dv_aucs.mrd_classification_id = mrd_classification.id
            LEFT JOIN books ON dv_aucs.book_id = books.id
            LEFT JOIN process_ors ON dv_aucs_entries.process_ors_id  = process_ors.id
            LEFT JOIN cte_gd_cash ON dv_aucs.id = cte_gd_cash.fk_dv_aucs_id
            LEFT JOIN (SELECT 
            process_ors_entries.process_ors_id,
            SUM(process_ors_entries.amount) as total_ors
            FROM 
            process_ors_entries
            GROUP BY process_ors_entries.process_ors_id
            ) as ors_amount ON process_ors.id = ors_amount.process_ors_id
            LEFT JOIN (
            SELECT 
            dv_aucs_entries.dv_aucs_id,
            SUM(dv_aucs_entries.amount_disbursed) as total_disbursed 
            FROM dv_aucs_entries 
            WHERE 
            dv_aucs_entries.is_deleted = 0
            GROUP BY dv_aucs_entries.dv_aucs_id
            )as  disbursed ON dv_aucs.id= disbursed.dv_aucs_id
            WHERE
            dv_aucs_entries.is_deleted = 0
            AND dv_aucs.reporting_period LIKE CONCAT(yr,'%');
            END 
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
        echo "m230929_051543_create_prc_GetDetailedDv_procedure cannot be reverted.\n";

        return false;
    }
    */
}
