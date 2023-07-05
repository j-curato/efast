<?php

use yii\db\Migration;

/**
 * Class m230705_063300_create_no_po_transmittal_liqs_view
 */
class m230705_063300_create_no_po_transmittal_liqs_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS vw_no_po_transmittal_liqs;
        CREATE VIEW vw_no_po_transmittal_liqs as SELECT 
        liquidation.id,
        liquidation.check_date,
        liquidation.check_number,
        liquidation.dv_number,
        liquidation.reporting_period,
        IFNULL(liquidation.payee,po_transaction.payee) as payee,
        IFNULL(liquidation.particular,po_transaction.particular)as particular,
        CONCAT(bank_account.account_number,'-',bank_account.account_name) as account_name,
        COALESCE(total_liq.total_withdrawal,0) as total_withdrawal,
        COALESCE(total_liq.total_expanded,0) as total_expanded,
        COALESCE(total_liq.total_liquidation_damage,0) as total_liquidation_damage,
        COALESCE(total_liq.total_vat,0) as total_vat,
        COALESCE(total_liq.gross_payment,0) as gross_payment,
        office.office_name as province
        FROM liquidation
        LEFT JOIN po_transaction ON liquidation.po_transaction_id = po_transaction.id
        LEFT JOIN check_range ON liquidation.check_range_id = check_range.id
        LEFT JOIN bank_account ON check_range.bank_account_id = bank_account.id
        LEFT JOIN office ON bank_account.fk_office_id = office.id
        LEFT JOIN (
        SELECT SUM(liquidation_entries.withdrawals) as total_withdrawal,
        SUM(liquidation_entries.expanded_tax) as total_expanded,
        SUM(liquidation_entries.vat_nonvat) as total_vat,
        SUM(liquidation_entries.liquidation_damage) as total_liquidation_damage,
        COALESCE(SUM(liquidation_entries.withdrawals),0) +
        COALESCE(SUM(liquidation_entries.expanded_tax),0) +
        COALESCE(SUM(liquidation_entries.vat_nonvat),0) +
        COALESCE(SUM(liquidation_entries.liquidation_damage),0)  as gross_payment,
        liquidation_entries.liquidation_id
        FROM liquidation_entries
        GROUP BY liquidation_entries.liquidation_id
        ) as total_liq ON liquidation.id= total_liq.liquidation_id
        WHERE 
        liquidation.is_cancelled  = 0
        AND NOT EXISTS (SELECT 
        po_transmittal_entries.liquidation_id
        FROM po_transmittal_entries
        WHERE 
        po_transmittal_entries.is_deleted  = 0
        AND po_transmittal_entries.is_returned = 0
        AND po_transmittal_entries.liquidation_id = liquidation.id
        )
        AND liquidation.reporting_period >='2022-01'
        AND total_liq.gross_payment >0
        ORDER BY office.office_name, liquidation.check_date DESC; ")
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
        echo "m230705_063300_create_no_po_transmittal_liqs_view cannot be reverted.\n";

        return false;
    }
    */
}
