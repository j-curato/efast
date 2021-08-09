<?php

use yii\db\Migration;

/**
 * Class m210808_072958_create_cibr_procedure
 */
class m210808_072958_create_cibr_procedure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
            CREATE PROCEDURE cibr_function (province VARCHAR(20),r_period VARCHAR(20))
            BEGIN 
                
                SELECT
                liquidation.check_date,
                liquidation.check_number,
                liquidation.particular,
                0 as amount,
                liquidation_entries.withdrawals,
                liquidation_entries.vat_nonvat,
                liquidation_entries.expanded_tax,
                chart_of_accounts.uacs as gl_object_code,
                chart_of_accounts.general_ledger as gl_account_title, 
                liquidation_entries.reporting_period

                FROM liquidation_entries
                LEFT JOIN liquidation ON liquidation_entries.liquidation_id= liquidation.id
                LEFT JOIN advances_entries ON liquidation_entries.advances_entries_id =advances_entries.id
                LEFT JOIN advances ON advances_entries.advances_id=advances.id
                LEFT JOIN chart_of_accounts ON liquidation_entries.chart_of_account_id = chart_of_accounts.id


                WHERE

                advances.province = province
                AND liquidation_entries.reporting_period = r_period
                UNION ALL

                SELECT 

                '' as check_date,
                '' as check_number,
                '' as particular,
                advances_entries.amount,
                0 as withdrawals,
                0 as vat_nonvat,
                0 as expanded_tax,
                '' as gl_object_code,
                '' as gl_account_title, 
                advances_entries.reporting_period

                FROM advances_entries
                LEFT JOIN advances ON advances_entries.advances_id=advances.id

                WHERE

                advances.province = province
                AND advances_entries.reporting_period = r_period;
            END

        SQL;

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP PROCEDURE IF EXISTS cibr_function")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210808_072958_create_cibr_procedure cannot be reverted.\n";

        return false;
    }
    */
}
