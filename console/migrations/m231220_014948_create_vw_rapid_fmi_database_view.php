<?php

use yii\db\Migration;

/**
 * Class m231220_014948_create_vw_rapid_fmi_database_view
 */
class m231220_014948_create_vw_rapid_fmi_database_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP VIEW IF EXISTS vw_rapid_fmi_database;
            CREATE VIEW vw_rapid_fmi_database as 
                SELECT 
                tbl_fmi_subprojects.serial_number,
                provinces.province_name,
                municipalities.municipality_name,
                barangays.barangay_name,
                tbl_fmi_subprojects.purok,
                tbl_fmi_batches.batch_name,
                tbl_fmi_subprojects.project_duration,
                tbl_fmi_subprojects.project_road_length,
                tbl_fmi_subprojects.project_start_date,
                tbl_fmi_subprojects.bank_account_name,
                tbl_fmi_subprojects.bank_account_number,
                tbl_fmi_subprojects.project_name,
                bank_branch_details.bank_manager,
                bank_branch_details.address,
                bank_branches.branch_name,
                banks.`name` as bank_name,
                grant_deposits.total_grant_deposit,
                equity_deposits.total_deposit_equity,
                other_deposits.total_deposit_other,
                liquidated.total_liquidated_equity,
                liquidated.total_liquidated_grant,
                liquidated.total_liquidated_other,

                COALESCE(grant_deposits.total_grant_deposit,0) - COALESCE(liquidated.total_liquidated_grant,0) as grant_beginning_balance,
                COALESCE(equity_deposits.total_deposit_equity,0) - COALESCE(liquidated.total_liquidated_equity,0) as equity_beginning_balance,
                COALESCE(other_deposits.total_deposit_other,0) -COALESCE(liquidated.total_liquidated_other,0) as other_beginning_balance,
                tbl_fmi_bank_account_closures.bank_certification_link,
                tbl_fmi_project_completions.certificate_of_project_link,
                tbl_fmi_project_completions.certificate_of_turnover_link,
                tbl_fmi_project_completions.spcr_link
                FROM tbl_fmi_subprojects
                LEFT JOIN municipalities ON tbl_fmi_subprojects.fk_municipality_id = municipalities.id
                LEFT JOIN provinces ON tbl_fmi_subprojects.fk_province_id = provinces.id
                LEFT JOIN barangays ON tbl_fmi_subprojects.fk_barangay_id  = barangays.id
                LEFT JOIN tbl_fmi_batches ON tbl_fmi_subprojects.fk_fmi_batch_id  = tbl_fmi_batches.id
                LEFT JOIN bank_branch_details ON tbl_fmi_subprojects.fk_bank_branch_detail_id = bank_branch_details.id
                LEFT JOIN bank_branches ON bank_branch_details.fk_bank_branch_id = bank_branches.id
                LEFT JOIN banks ON bank_branches.fk_bank_id = banks.id
                LEFT JOIN tbl_fmi_project_completions ON tbl_fmi_subprojects.id = tbl_fmi_project_completions.fk_fmi_subproject_id
                LEFT JOIN tbl_fmi_bank_account_closures ON tbl_fmi_subprojects.id = tbl_fmi_bank_account_closures.fk_fmi_subproject_id
                LEFT JOIN 
                (SELECT 
                tbl_fmi_lgu_liquidations.fk_fmi_subproject_id,
                SUM(tbl_fmi_lgu_liquidation_items.equity_amount) as total_liquidated_equity,
                SUM(tbl_fmi_lgu_liquidation_items.grant_amount) as total_liquidated_grant,
                SUM(tbl_fmi_lgu_liquidation_items.other_fund_amount) as total_liquidated_other
                FROM `tbl_fmi_lgu_liquidations`
                JOIN tbl_fmi_lgu_liquidation_items ON tbl_fmi_lgu_liquidations.id = tbl_fmi_lgu_liquidation_items.fk_fmi_lgu_liquidation_id
                WHERE 
                tbl_fmi_lgu_liquidation_items.is_deleted  = 0

                GROUP BY tbl_fmi_lgu_liquidations.fk_fmi_subproject_id) as liquidated ON tbl_fmi_subprojects.id = liquidated.fk_fmi_subproject_id
                LEFT JOIN (SELECT 
                tbl_fmi_fund_releases.fk_fmi_subproject_id,
                COALESCE(SUM(dv_aucs_entries.amount_disbursed),0) as total_grant_deposit
                FROM  tbl_fmi_fund_releases 
                JOIN cash_disbursement ON tbl_fmi_fund_releases.fk_cash_disbursement_id = cash_disbursement.id
                JOIN cash_disbursement_items  ON cash_disbursement.id = cash_disbursement_items.fk_cash_disbursement_id
                JOIN dv_aucs ON cash_disbursement_items.fk_dv_aucs_id = dv_aucs.id
                JOIN dv_aucs_entries ON dv_aucs.id = dv_aucs_entries.dv_aucs_id
                WHERE cash_disbursement_items.is_deleted  = 0
                AND dv_aucs_entries.is_deleted = 0
                GROUP BY tbl_fmi_fund_releases.fk_fmi_subproject_id ) grant_deposits ON tbl_fmi_subprojects.id = grant_deposits.fk_fmi_subproject_id
                LEFT JOIN (SELECT 
                tbl_fmi_bank_deposits.fk_fmi_subproject_id,
                SUM(tbl_fmi_bank_deposits.deposit_amount) as total_deposit_equity
                FROM tbl_fmi_bank_deposits
                JOIN tbl_fmi_bank_deposit_types ON tbl_fmi_bank_deposits.fk_fmi_bank_deposit_type_id = tbl_fmi_bank_deposit_types.id
                WHERE 
                tbl_fmi_bank_deposit_types.deposit_type ='LGU Equity'
                GROUP BY tbl_fmi_bank_deposits.fk_fmi_subproject_id) as equity_deposits ON tbl_fmi_subprojects.id = equity_deposits.fk_fmi_subproject_id
                LEFT JOIN (SELECT 
                tbl_fmi_bank_deposits.fk_fmi_subproject_id,
                SUM(tbl_fmi_bank_deposits.deposit_amount) as total_deposit_other
                FROM tbl_fmi_bank_deposits
                JOIN tbl_fmi_bank_deposit_types ON tbl_fmi_bank_deposits.fk_fmi_bank_deposit_type_id = tbl_fmi_bank_deposit_types.id
                WHERE 
                tbl_fmi_bank_deposit_types.deposit_type ='Other Bank Deposits'
                GROUP BY tbl_fmi_bank_deposits.fk_fmi_subproject_id) as other_deposits ON tbl_fmi_subprojects.id = other_deposits.fk_fmi_subproject_id
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
        echo "m231220_014948_create_vw_rapid_fmi_database_view cannot be reverted.\n";

        return false;
    }
    */
}
