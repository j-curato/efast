<?php

use yii\db\Migration;

/**
 * Class m220419_010256_update_cibr_liquidation_balances_view
 */
class m220419_010256_update_cibr_liquidation_balances_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql=<<<SQL
            DROP VIEW IF EXISTS cibr_liquidation_balances;
            CREATE VIEW cibr_liquidation_balances as 
                SELECT
                liquidation.province,
                check_range.bank_account_id,
                liquidation_entries.reporting_period,
                SUM(liquidation_entries.withdrawals) as total_withdrawals
                FROM 
                liquidation_entries
                LEFT JOIN liquidation ON liquidation_entries.liquidation_id = liquidation.id
                LEFT JOIN check_range ON liquidation.check_range_id = check_range.id

                GROUP BY
                liquidation.province,
                check_range.bank_account_id,
                liquidation_entries.reporting_period 
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
        echo "m220419_010256_update_cibr_liquidation_balances_view cannot be reverted.\n";

        return false;
    }
    */
}
