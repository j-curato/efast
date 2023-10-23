<?php

use yii\db\Migration;

/**
 * Class m231023_083346_create_vw_transactionsTracking_view
 */
class m231023_083346_create_vw_transactionsTracking_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP VIEW IF EXISTS vw_transactionsTracking;
            CREATE VIEW vw_transactionsTracking as 
                SELECT 
                `transaction`.tracking_number as transactionNum,
                `transaction`.transaction_date as transactionDate,
                responsibility_center.`name` as responsibilityCenter,
                payee.registered_name as payee,
                processOrs.orsNum,
                dvAucs.dv_number,
                cashDisbursements.checkNum,
                cashDisbursements.adaNum,
                cashDisbursements.cashIsCancelled,
                hasAcic.acicNum,
                acicInBanks.acicInBankNum,
                acicInBanks.acicInBankDate,
                (CASE 
                    WHEN acicInBanks.acicInBankNum IS NOT NULL THEN 'in Bank'
                    WHEN cashDisbursements.checkNum IS NOT NULL THEN 'at Cash'
                    WHEN dvAucs.dv_number IS NOT NULL THEN 'at Accounting'
                    WHEN processOrs.orsNum IS NOT NULL THEN 'at Budget'
                    ELSE 'Still at end-user'
                END) as dvStatus


                FROM `transaction`
                LEFT JOIN responsibility_center ON `transaction`.responsibility_center_id = responsibility_center.id
                LEFT JOIN payee ON `transaction`.payee_id = payee.id
                LEFT JOIN (SELECT 
                process_ors.id,
                process_ors.transaction_id,
                process_ors.serial_number as orsNum
                FROM process_ors
                WHERE process_ors.is_cancelled = 0) as processOrs ON `transaction`.id = processOrs.transaction_id
                LEFT JOIN (SELECT 
                dv_aucs.id,
                dv_aucs.dv_number,
                dv_aucs.in_timestamp,
                dv_aucs.out_timestamp,
                dv_aucs_entries.process_ors_id

                FROM dv_aucs 
                LEFT JOIN dv_aucs_entries ON dv_aucs.id = dv_aucs_entries.dv_aucs_id
                WHERE 
                dv_aucs_entries.is_deleted = 0
                AND dv_aucs.is_cancelled = 0) as dvAucs ON processOrs.id = dvAucs.process_ors_id
                LEFT JOIN (
                SELECT 
                cash_disbursement.id,
                cash_disbursement.check_or_ada_no as checkNum,
                cash_disbursement.ada_number as adaNum,
                cash_disbursement.is_cancelled as cashIsCancelled,
                cash_disbursement_items.fk_dv_aucs_id
                FROM cash_disbursement
                JOIN cash_disbursement_items ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
                WHERE cash_disbursement_items.is_deleted = 0
                AND  NOT EXISTS (SELECT c.id FROM cash_disbursement as c WHERE c.is_cancelled = 1 AND c.parent_disbursement = cash_disbursement.id)
                AND cash_disbursement.is_cancelled = 0
                )as cashDisbursements ON dvAucs.id = cashDisbursements.fk_dv_aucs_id
                LEFT JOIN (SELECT 
                acics_cash_items.fk_cash_disbursement_id as cashDisbursementId,
                acics.id,
                acics.serial_number  as acicNum
                FROM acics
                JOIN acics_cash_items ON acics.id = acics_cash_items.fk_acic_id
                WHERE acics_cash_items.is_deleted = 0) as hasAcic ON cashDisbursements.id = hasAcic.cashDisbursementId
                LEFT JOIN (SELECT 
                acic_in_bank.serial_number as acicInBankNum,
                acic_in_bank.`date` as acicInBankDate,
                acic_in_bank_items.fk_acic_id
                FROM acic_in_bank
                JOIN acic_in_bank_items ON acic_in_bank.id = acic_in_bank_items.fk_acic_in_bank_id
                WHERE 
                acic_in_bank_items.is_deleted = 0) as acicInBanks ON hasAcic.id = acicInBanks.fk_acic_id
                ORDER BY `transaction`.created_at DESC
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
        echo "m231023_083346_create_vw_transactionsTracking_view cannot be reverted.\n";

        return false;
    }
    */
}
