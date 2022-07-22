<?php

use yii\db\Migration;

/**
 * Class m220418_051319_update_liquidation_entries_view
 */
class m220418_051319_update_liquidation_entries_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql =<<<SQL
            DROP VIEW IF EXISTS liquidation_entries_view;
            CREATE VIEW liquidation_entries_view as 
                    SELECT 
                liquidation_entries.id,
                liquidation.reporting_period as orig_reporting_period,
                liquidation.dv_number,
                liquidation_entries.reporting_period,
                liquidation.check_date,
                liquidation.check_number,
                advances_entries.fund_source,
            
                        (CASE 
                        WHEN liquidation.particular='' OR liquidation.particular IS NULL THEN po_transaction.particular
                            ELSE po_transaction.particular
                        END) as particular,
                        (CASE 
                        WHEN liquidation.payee='' OR liquidation.payee IS NULL  THEN  po_transaction.payee
                            ELSE liquidation.payee
                        END) as payee,

                CONCAT(bank_account.account_number,'-',bank_account.account_name) as bank_account,
                (
                CASE 
                WHEN liquidation_entries.new_object_code IS NOT NULL THEN  liquidation_entries.new_object_code
                WHEN liquidation_entries.new_chart_of_account_id IS NOT NULL THEN chart_of_account2.uacs
                ELSE chart_of_account1.uacs
                END
                ) as object_code,
                (
                CASE 
                WHEN liquidation_entries.new_object_code IS NOT NULL THEN  accounting_codes.account_title
                WHEN liquidation_entries.new_chart_of_account_id IS NOT NULL THEN chart_of_account2.general_ledger
                ELSE chart_of_account1.general_ledger
                END
                ) as account_title,


                    liquidation_entries.withdrawals,
                    liquidation_entries.vat_nonvat,
                    liquidation_entries.expanded_tax,
                    liquidation_entries.liquidation_damage,
                    COALESCE(IFNULL(liquidation_entries.withdrawals,0))
                    + COALESCE(IFNULL(liquidation_entries.vat_nonvat,0))
                    +COALESCE(IFNULL(liquidation_entries.expanded_tax,0)) as gross_payment,
                    liquidation.province
                    
                FROM liquidation
                    LEFT JOIN liquidation_entries ON
                    liquidation.id= liquidation_entries.liquidation_id
                    LEFT JOIN po_transaction ON liquidation.po_transaction_id = po_transaction.id
                    LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id =advances_entries.id
                    LEFT JOIN advances ON advances_entries.advances_id=advances.id
                    LEFT JOIN chart_of_accounts as  chart_of_account1   ON liquidation_entries.chart_of_account_id = chart_of_account1.id
                LEFT JOIN chart_of_accounts  as chart_of_account2 ON liquidation_entries.new_chart_of_account_id = chart_of_account2.id
                LEFT JOIN accounting_codes ON liquidation_entries.new_object_code = accounting_codes.object_code
                    LEFT JOIN check_range ON liquidation.check_range_id = check_range.id
                    LEFT JOIN bank_account ON check_range.bank_account_id = bank_account.id 
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
        echo "m220418_051319_update_liquidation_entries_view cannot be reverted.\n";

        return false;
    }
    */
}
