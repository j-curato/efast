<?php

use yii\db\Migration;

/**
 * Class m220419_031835_update_cdr_liquidation_balance_view
 */
class m220419_031835_update_cdr_liquidation_balance_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            DROP VIEW IF EXISTS cdr_liquidation_balance;
            CREATE VIEW cdr_liquidation_balance as 
            SELECT
                liquidation.province,
                check_range.bank_account_id,
                liquidation_entries.reporting_period,
                advances_entries.report_type,
                SUM(liquidation_entries.withdrawals) as total_withdrawals,
                SUM(liquidation_entries.vat_nonvat) as total_vat,
                SUM(liquidation_entries.expanded_tax) as total_expanded,
                SUM(liquidation_entries.liquidation_damage) as total_liquidation_damage
                FROM liquidation_entries

                LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id = advances_entries.id
                        INNER JOIN liquidation ON liquidation_entries.liquidation_id = liquidation.id
                        INNER JOIN check_range ON liquidation.check_range_id = check_range.id
                GROUP BY 
                liquidation.province,
                        check_range.bank_account_id,
                liquidation_entries.reporting_period,
                advances_entries.report_type 
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
        echo "m220419_031835_update_cdr_liquidation_balance_view cannot be reverted.\n";

        return false;
    }
    */
}
