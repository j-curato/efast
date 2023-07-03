<?php

use yii\db\Migration;

/**
 * Class m230703_084037_update_prc_Cadadr_procedure
 */
class m230703_084037_update_prc_Cadadr_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
        DROP PROCEDURE IF EXISTS prc_Cadadr;
        CREATE PROCEDURE prc_Cadadr(frm_prd VARCHAR(20),to_prd VARCHAR(20),book_id INT)
        BEGIN
        SELECT * FROM (
            SELECT 
            mode_of_payments.`name` as mode_of_payment_name,
            cash_disbursement.reporting_period,
            dv_aucs_index.dv_number,
            cash_disbursement.check_or_ada_no,
            IFNULL(cash_disbursement.ada_number,'') as ada_number,
            cash_disbursement.issuance_date as check_date,
            dv_aucs_index.payee,
            dv_aucs_index.particular,
            books.`name` as book_name,
            IFNULL(CONCAT(chart_of_accounts.uacs,'-',chart_of_accounts.general_ledger),'') as uacs,
            0 as nca_receive,
            (CASE
            WHEN 
                cash_disbursement.is_cancelled = 1
            THEN  
                    (CASE
                        WHEN  books.type = 'mds regular'
                        THEN 
                            (CASE 
                                    WHEN 	 
                                        QUARTER(CONCAT(cash_disbursement.reporting_period,'-01')) = QUARTER(CONCAT(parent_cash.reporting_period,'-01')) 
                                        AND SUBSTRING_INDEX(cash_disbursement.reporting_period,'-',1) = SUBSTRING_INDEX(parent_cash.reporting_period,'-',1) 
                                    THEN  dv_aucs_index.ttlAmtDisbursed *-1
                                    ELSE 0
                                END)
                        WHEN books.type = 'mds trust'
                        THEN 
                                (CASE 
                                    WHEN SUBSTRING_INDEX(cash_disbursement.reporting_period,'-',1) = SUBSTRING_INDEX(parent_cash.reporting_period,'-',1)
                                    THEN dv_aucs_index.ttlAmtDisbursed *-1
                                    ELSE 0
                                END)
                        ELSE dv_aucs_index.ttlAmtDisbursed *-1
                    END)
            ELSE dv_aucs_index.ttlAmtDisbursed
            END) as amtDisbursed,
            cash_disbursement.is_cancelled,
            (CASE
            WHEN mode_of_payments.`name` LIKE '%w/o ada%' THEN 1
            ELSE 0
            END) as is_check
            FROM cash_disbursement
            JOIN cash_disbursement_items ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
            JOIN dv_aucs_index ON cash_disbursement_items.fk_dv_aucs_id = dv_aucs_index.id
            LEFT JOIN chart_of_accounts ON cash_disbursement_items.fk_chart_of_account_id = chart_of_accounts.id
            LEFT JOIN mode_of_payments ON cash_disbursement.fk_mode_of_payment_id = mode_of_payments.id
            LEFT JOIN books ON cash_disbursement.book_id = books.id
            LEFT JOIN lddap_adas ON cash_disbursement.id = lddap_adas.fk_cash_disbursement_id
            LEFT JOIN cash_disbursement as parent_cash ON cash_disbursement.parent_disbursement = parent_cash.id
            WHERE 
            cash_disbursement_items.is_deleted = 0
            AND cash_disbursement.reporting_period >= frm_prd
            AND cash_disbursement.reporting_period <= to_prd
            AND books.id = book_id
            UNION 
            SELECT
            '' as mode_of_payment,
            cash_received.reporting_period,
            '' as dv_number,
            '' as check_or_ada_no,
            '' as ada_number,
            cash_received.date as check_date,
            (
            CASE 
            WHEN cash_received.nca_no IS NOT NULL OR  cash_received.nca_no !='' THEN cash_received.nca_no
            WHEN cash_received.nft_no IS NOT NULL OR  cash_received.nft_no !='' THEN cash_received.nft_no
            WHEN cash_received.nta_no IS NOT NULL OR  cash_received.nta_no !='' THEN cash_received.nta_no
            END) as payee,
            cash_received.purpose as particular,
            books.`name` as  book_name,
            '' as uacs,
        
            cash_received.amount nca_recieve,
            0 as amtDisburse,
            0 as is_cancelled,
            '' as is_check
            FROM
            cash_received 
            LEFT JOIN books ON cash_received.book_id = books.id 
            WHERE 
            cash_received.reporting_period >= frm_prd
            AND cash_received.reporting_period <= to_prd
            AND books.id = book_id
        ) as q  ORDER BY q.check_date ; 
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
        echo "m230703_084037_update_prc_Cadadr_procedure cannot be reverted.\n";

        return false;
    }
    */
}
