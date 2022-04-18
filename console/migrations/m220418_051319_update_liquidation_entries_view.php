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
            IFNULL(liquidation.particular,po_transaction.particular) as particular,
                    IFNULL(liquidation.payee , po_transaction.payee ) as payee,
                    CONCAT(bank_account.account_number,'-',bank_account.account_name) as bank_account,
            chart_of_accounts.uacs as object_code,
            chart_of_accounts.general_ledger as account_title,
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
            LEFT JOIN chart_of_accounts ON liquidation_entries.chart_of_account_id = chart_of_accounts.id
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
