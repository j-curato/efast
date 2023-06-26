<?php

use yii\db\Migration;

/**
 * Class m230622_032548_create_prc_Cadadr_procedure
 */
class m230622_032548_create_prc_Cadadr_procedure extends Migration
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
                dv_aucs_index.dv_number,
                books.`name` as book_name,
                cash_disbursement.reporting_period,
                cash_disbursement.check_or_ada_no,
                IFNULL(cash_disbursement.ada_number,'') as ada_number,
                cash_disbursement.issuance_date as check_date,
                dv_aucs_index.payee,
                dv_aucs_index.particular,
                IFNULL(CONCAT(chart_of_accounts.uacs,'-',chart_of_accounts.general_ledger),'') as uacs,
                mode_of_payments.`name` as mode_of_payment_name,
                0 as nca_receive,
                (CASE
                WHEN mode_of_payments.`name` LIKE '%w/o ada%' THEN dv_aucs_index.ttlAmtDisbursed
                ELSE 0
                END) as check_issued,
                (CASE
                WHEN mode_of_payments.`name` LIKE '%w/ ada%' THEN dv_aucs_index.ttlAmtDisbursed
                ELSE 0
                END) as ada_issued,
                cash_disbursement.is_cancelled,
                gdRadai.serial_number as radai_no,
                gdRci.serial_number as rci_no
                FROM cash_disbursement
                JOIN cash_disbursement_items ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
                JOIN dv_aucs_index ON cash_disbursement_items.fk_dv_aucs_id = dv_aucs_index.id
                LEFT JOIN chart_of_accounts ON cash_disbursement_items.fk_chart_of_account_id = chart_of_accounts.id
                LEFT JOIN mode_of_payments ON cash_disbursement.fk_mode_of_payment_id = mode_of_payments.id
                LEFT JOIN books ON cash_disbursement.book_id = books.id
                LEFT JOIN lddap_adas ON cash_disbursement.id = lddap_adas.fk_cash_disbursement_id
                LEFT JOIN (SELECT 
                rci.serial_number,rci_items.fk_cash_disbursement_item_id 
                FROM rci 
                JOIN rci_items ON rci.id = rci_items.fk_rci_id
                WHERE 
                rci_items.is_deleted = 0) gdRci ON cash_disbursement_items.id = gdRci.fk_cash_disbursement_item_id

                LEFT JOIN (SELECT 
                radai.serial_number,
                radai_items.fk_lddap_ada_id
                FROM radai
                JOIN radai_items ON radai.id = radai_items.fk_radai_id
                WHERE 
                radai_items.is_deleted = 0) gdRadai ON lddap_adas.id = gdRadai.fk_lddap_ada_id
                WHERE 
                cash_disbursement_items.is_deleted = 0
                -- AND (gdRadai.serial_number IS NOT NULL OR gdRci.serial_number IS NOT NULL )
                AND cash_disbursement.reporting_period >= frm_prd
                AND cash_disbursement.reporting_period <= to_prd
                AND books.id = book_id
                UNION 
                 SELECT
                '' as dv_number,
                books.`name` as  book_name,
                cash_received.reporting_period,
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
                '' as uacs,
                '' as mode_of_payment,
                cash_received.amount nca_recieve,
                0 as check_issued,
                0 as ada_issued,
                0 as is_cancelled,
                '',
                ''
        
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
        // Yii::$app->db->createCommand("DROP PROCEDURE IF EXISTS prc_Cadadr;
        // DELIMITER //
        // CREATE PROCEDURE prc_Cadadr(frm_prd VARCHAR(20),to_prd VARCHAR(20),book_id INT)
        // BEGIN
        //         SELECT 
        //         dv_aucs_index.dv_number,
        //         books.`name` as book_name,
        //         cash_disbursement.reporting_period,
        //         cash_disbursement.check_or_ada_no,
        //         cash_disbursement.ada_number,
        //         cash_disbursement.issuance_date as check_date,
        //         dv_aucs_index.payee,
        //         dv_aucs_index.particular,
        //         CONCAT(chart_of_accounts.uacs,'-',chart_of_accounts.general_ledger) as uacs,
        //         mode_of_payments.`name` as mode_of_payment_name,

        //         0 as nca_receive,
        //         (CASE
        //             WHEN mode_of_payments.`name` LIKE '%w/o ada%' THEN dv_aucs_index.ttlAmtDisbursed
        //         ELSE 0
        //         END) as check_issued,
        //         (CASE
        //             WHEN mode_of_payments.`name` LIKE '%w/ ada%' THEN dv_aucs_index.ttlAmtDisbursed
        //         ELSE 0
        //         END) as ada_issued,
        //         cash_disbursement.is_cancelled
        //         FROM cash_disbursement
        //         JOIN cash_disbursement_items ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
        //         JOIN dv_aucs_index ON cash_disbursement_items.fk_dv_aucs_id = dv_aucs_index.id
        //         LEFT JOIN chart_of_accounts ON cash_disbursement_items.fk_chart_of_account_id = chart_of_accounts.id
        //         LEFT JOIN mode_of_payments ON cash_disbursement.fk_mode_of_payment_id = mode_of_payments.id
        //         LEFT JOIN books ON cash_disbursement.book_id = books.id
        //         WHERE 
        //         cash_disbursement_items.is_deleted = 0
        //         AND cash_disbursement.reporting_period >= frm_prd
        //         AND cash_disbursement.reporting_period <= to_prd
        //         AND books.id = book_id
        //         UNION 
        //          SELECT
        //         ''dv_number,
        //         books.`name` as  book_name,
        //         cash_received.reporting_period,
        //         '' as check_or_ada_no,
        //         '' as ada_number,
        //         cash_received.date as check_date,
        //         (
        //         CASE 
        //         WHEN cash_received.nca_no IS NOT NULL OR  cash_received.nca_no !='' THEN cash_received.nca_no
        //         WHEN cash_received.nft_no IS NOT NULL OR  cash_received.nft_no !='' THEN cash_received.nft_no
        //         WHEN cash_received.nta_no IS NOT NULL OR  cash_received.nta_no !='' THEN cash_received.nta_no
        //         END) as payee,
        //         cash_received.purpose as particular,
        //         '' as uacs,
        //         '' as mode_of_payment,
        //         cash_received.amount nca_recieve,
        //         0 as check_issued,
        //         0 as ada_issued,
        //         0 as is_cancelled

        //         FROM
        //         cash_received 
        //         LEFT JOIN books ON cash_received.book_id = books.id 
        //         WHERE 
        //         cash_received.reporting_period >= frm_prd
        //         AND cash_received.reporting_period <= to_prd
        //         AND books.id = book_id;

        // END //
        // DELIMITER ;")
        // ->query();
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
        echo "m230622_032548_create_prc_Cadadr_procedure cannot be reverted.\n";

        return false;
    }
    */
}
