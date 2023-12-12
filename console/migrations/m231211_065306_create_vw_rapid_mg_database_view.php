<?php

use yii\db\Migration;

/**
 * Class m231211_065306_create_vw_rapid_mg_database_view
 */
class m231211_065306_create_vw_rapid_mg_database_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP VIEW IF EXISTS vw_rapid_mg_database;
            CREATE VIEW vw_rapid_mg_database as 

                SELECT 
                mgrfrs.id,
                office.office_name,
                provinces.province_name,
                municipalities.municipality_name,
                barangays.barangay_name,
                mgrfrs.organization_name,
                mgrfrs.purok,
                mgrfrs.authorized_personnel,
                mgrfrs.contact_number,
                mgrfrs.saving_account_number,
                mgrfrs.email_address,
                mgrfrs.investment_type,
                mgrfrs.investment_description,
                mgrfrs.project_consultant,
                mgrfrs.project_objective,
                mgrfrs.project_beneficiary,
                mgrfrs.matching_grant_amount,
                mgrfrs.equity_amount,
                bank_branch_details.bank_manager,
                bank_branch_details.address,
                banks.`name` as bank_name,
                COALESCE(deposit.total_deposit_equity,0) as total_deposit_equity,
                COALESCE(deposit.total_deposit_grant,0) as total_deposit_grant ,
                COALESCE(deposit.total_deposit_other_amount,0) as total_deposit_other_amount,
                COALESCE(liquidated.total_liquidation_grant,0) as total_liquidation_grant,
                COALESCE(liquidated.total_liquidation_equity,0) as total_liquidation_equity,
                COALESCE(liquidated.total_liquidation_other_amount,0)  as total_liquidation_other_amount,
                COALESCE(deposit.total_deposit_equity,0) - COALESCE(liquidated.total_liquidation_equity,0) as balance_equity,
                COALESCE(deposit.total_deposit_grant,0) - COALESCE(liquidated.total_liquidation_grant,0) as balance_grant,
                COALESCE(deposit.total_deposit_other_amount,0) - COALESCE(liquidated.total_liquidation_other_amount,0) as balance_other_amount,
                (SELECT 

                COUNT(tbl_notification_to_pay.id) as notificationToPayCount
                FROM 
                mgrfrs as mg
                JOIN due_diligence_reports ON mgrfrs.id = due_diligence_reports.fk_mgrfr_id
                JOIN tbl_notification_to_pay ON due_diligence_reports.id = tbl_notification_to_pay.fk_due_diligence_report_id
                WHERE mg.id = mgrfrs.id 
                GROUP BY
                mgrfrs.id) as notification_to_pay_count,
                (SELECT 
                COUNT(due_diligence_reports.id) as notificationToPayCount
                FROM 
                mgrfrs as mg
                JOIN due_diligence_reports ON mgrfrs.id = due_diligence_reports.fk_mgrfr_id
                WHERE mg.id = mgrfrs.id 
                GROUP BY
                mgrfrs.id) as due_diligence_report_count
                FROM mgrfrs

                LEFT JOIN provinces ON mgrfrs.fk_office_id = provinces.id
                LEFT JOIN municipalities ON mgrfrs.fk_municipality_id = municipalities.id
                LEFT JOIN barangays ON mgrfrs.fk_barangay_id = barangays.id
                LEFT JOIN office ON mgrfrs.fk_office_id = office.id
                LEFT JOIN bank_branch_details ON mgrfrs.fk_bank_branch_detail_id = bank_branch_details.id
                LEFT JOIN bank_branches ON bank_branch_details.fk_bank_branch_id = bank_branches.id
                LEFT JOIN banks ON bank_branches.fk_bank_id = banks.id
                LEFT JOIN(SELECT 
                due_diligence_reports.fk_mgrfr_id,
                COALESCE(SUM(tbl_notification_to_pay.equity_amount),0) as total_liquidation_equity,
                COALESCE(SUM(tbl_notification_to_pay.matching_grant_amount),0) as total_liquidation_grant,
                COALESCE(SUM(tbl_notification_to_pay.other_amount),0) as total_liquidation_other_amount
                FROM 
                mgrfrs
                JOIN due_diligence_reports ON mgrfrs.id = due_diligence_reports.fk_mgrfr_id
                JOIN tbl_notification_to_pay ON due_diligence_reports.id = tbl_notification_to_pay.fk_due_diligence_report_id
                JOIN tbl_mg_liquidation_items ON tbl_notification_to_pay.id = tbl_mg_liquidation_items.fk_notification_to_pay_id
                JOIN tbl_mg_liquidations ON tbl_mg_liquidation_items.fk_mg_liquidation_id = tbl_mg_liquidations.id
                WHERE 
                tbl_mg_liquidation_items.is_deleted = 0
                -- AND tbl_mg_liquidations.reporting_period = '2023-02'
                GROUP BY
                due_diligence_reports.fk_mgrfr_id) as liquidated ON mgrfrs.id = liquidated.fk_mgrfr_id
                LEFT JOIN (
                SELECT 
                cash_deposits.fk_mgrfr_id,
                COALESCE(SUM(cash_deposits.equity_amount),0) as total_deposit_equity,
                COALESCE(SUM(cash_deposits.matching_grant_amount),0) as total_deposit_grant,
                COALESCE(SUM(cash_deposits.other_amount),0) as total_deposit_other_amount
                FROM cash_deposits
                --  WHERE cash_deposits.reporting_period = '2023-02'
                GROUP BY
                cash_deposits.fk_mgrfr_id) as deposit ON mgrfrs.id = deposit.fk_mgrfr_id
                ORDER BY mgrfrs.id

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
        echo "m231211_065306_create_vw_rapid_mg_database_view cannot be reverted.\n";

        return false;
    }
    */
}
